<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class InstallationController extends Controller
{
    /**
     * VYLEPŠENÝ Dvojitý zámek instalátoru
     */
    private function checkInstallationLock()
    {
        // 1. Fyzický zámek na disku
        if (file_exists(storage_path('installed'))) {
            abort(403, 'CMS je již nainstalováno. Z bezpečnostních důvodů je instalátor uzamčen.');
        }

        // Pokud v systému chybí jakékoliv nastavení DB, nemáme co ověřovat, web 100% není nainstalovaný
        if (!config('database.connections.mysql.database')) {
            return;
        }

        // 2. Dynamický zámek v databázi
        try {
            // Zjišťujeme, zda existuje tabulka users A ZÁROVEŇ zda v ní je už nějaký uživatel.
            // Díky tomu nás to nevyhodí po vytvoření prázdných tabulek v kroku 3!
            if (Schema::hasTable('users')) {
                if (DB::table('users')->count() > 0) {
                    file_put_contents(storage_path('installed'), 'Automaticky uzamčeno dne: ' . now() . ' (Detekován funkční web)');
                    abort(403, 'CMS je již nainstalováno. Z bezpečnostních důvodů je instalátor uzamčen.');
                }
            }
        } catch (\Exception $e) {
            // Pokud spojení selže, je to u čisté instalace v pořádku.
        }
    }

    public function showDatabaseForm()
    {
        $this->checkInstallationLock();
        return view('install.database');
    }

    public function processDatabase(Request $request)
    {
        $this->checkInstallationLock();
        
        $validated = $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|string',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'db_prefix'   => 'nullable|string|regex:/^[a-zA-Z0-9_]+$/',
        ]);

        config(['database.connections.test_connection' => [
            'driver'    => 'mysql',
            'host'      => $validated['db_host'],
            'port'      => $validated['db_port'],
            'database'  => $validated['db_database'],
            'username'  => $validated['db_username'],
            'password'  => $validated['db_password'] ?? '',
            'prefix'    => $validated['db_prefix'] ?? '',
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]]);

        try {
            DB::connection('test_connection')->getPdo();
            
            // Pojistka: Pokud uživatel zadá existující databázi a prefix, kde už web běží
            if (Schema::connection('test_connection')->hasTable('users') && DB::connection('test_connection')->table('users')->count() > 0) {
                return redirect()->back()->withInput()->withErrors(['database' => 'V této databázi pod tímto prefixem již existuje nainstalovaný systém. Zvolte jiný prefix tabulek (např. test_).']);
            }
            
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['database' => 'Nepodařilo se připojit k databázi. Zkontrolujte údaje. Chyba: ' . $e->getMessage()]);
        }

        $this->updateEnvFile('DB_CONNECTION', 'mysql');
        $this->updateEnvFile('DB_HOST', $validated['db_host']);
        $this->updateEnvFile('DB_PORT', $validated['db_port']);
        $this->updateEnvFile('DB_DATABASE', $validated['db_database']);
        $this->updateEnvFile('DB_USERNAME', $validated['db_username']);
        $this->updateEnvFile('DB_PASSWORD', $validated['db_password'] ?? '');
        $this->updateEnvFile('DB_PREFIX', $validated['db_prefix'] ?? '');

        // Vyčistíme starou paměť, aby Laravel okamžitě viděl nové údaje z .env
        Artisan::call('config:clear');

        return redirect()->route('install.migrations')->with('success', 'Databáze byla úspěšně připojena.');
    }

    public function runMigrations()
    {
        $this->checkInstallationLock();
        
        try {
            Artisan::call('migrate', ['--force' => true]);
            return redirect()->route('install.admin')->with('success', 'Databáze byla úspěšně vytvořena!');
        } catch (\Exception $e) {
            return redirect()->route('install.database')
                ->withErrors(['migrations' => 'Nastala chyba při vytváření tabulek: ' . $e->getMessage()]);
        }
    }

    public function showAdminForm()
    {
        $this->checkInstallationLock();
        return view('install.admin');
    }

public function processAdmin(Request $request)
    {
        $this->checkInstallationLock();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        // 1. Vytvoření administrátora
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 2. AUTOMATICKÝ OSEV VÝCHOZÍHO OBSAHU (PŘIDÁNO)
        // Použijeme tvůj model Page pro vytvoření úvodní stránky se slugem 'home'
        if (\Illuminate\Support\Facades\Schema::hasTable('pages')) {
            \App\Models\Page::create([
                'title' => 'Vítejte na Vašem novém webu!',
                'slug' => 'home',
                'published' => true,
                'content' => '
                    <div style="text-align: center; padding: 40px 20px; font-family: sans-serif;">
                        <h1 style="color: #2d3748; font-size: 2.5rem; margin-bottom: 20px;">🎉 Instalace CMS proběhla úspěšně!</h1>
                        <p style="color: #4a5568; font-size: 1.2rem; max-width: 600px; margin: 0 auto 30px auto; line-height: 1.6;">
                            Tento text vidíte proto, že systém automaticky vygeneroval výchozí úvodní stránku se slugem <strong>home</strong>. Nyní můžete přejít do administrace a začít tvořit svůj vlastní obsah.
                        </p>
                        <a href="/admin" style="display: inline-block; background-color: #3182ce; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; font-weight: bold; transition: background 0.2s;">
                            👉 Vstoupit do administrace
                        </a>
                    </div>
                '
            ]);
        }

        // 3. Vygenerování ostrého šifrovacího klíče a uzamčení webu
        Artisan::call('key:generate', ['--force' => true]);
        file_put_contents(storage_path('installed'), 'Nainstalováno dne: ' . now());

        return redirect('/')->with('success', 'Instalace byla úspěšně dokončena! Nyní se můžete přihlásit.');
    }

    /**
     * VYLEPŠENÝ PŘEPIS .ENV S HLÁŠENÍM CHYB
     */
    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $path);
            } else {
                touch($path); 
            }

            $content = file_get_contents($path);
            if (!str_contains($content, 'APP_KEY=')) {
                $content .= "\nAPP_KEY=base64:uQ2vF3pM7ZkWx9Yb4tN1vC8xJzR5qW3eE4rT7yU8iI0=";
                file_put_contents($path, $content);
            }
        }

        $content = file_get_contents($path);

        if (str_contains($value, ' ')) {
            $value = '"' . $value . '"';
        }

        // Spolehlivější nahrazení pomocí regulárního výrazu
        if (preg_match("/^{$key}=/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        // POKUD ZÁPIS SELŽE, OKAMŽITĚ ZASTAVÍME INSTALACI A OZNÁMÍME TO!
        if (file_put_contents($path, $content) === false) {
            abort(500, "KRITICKÁ CHYBA: Systém nemá právo zapisovat do souboru .env. Změňte oprávnění souboru (CHMOD) přes FTP na 664 nebo 777.");
        }
    }
}