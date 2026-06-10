<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class InstallationController extends Controller
{
    /**
     * Dvojitý zámek instalátoru - zavoláme na začátku každé veřejné metody
     */
    private function checkInstallationLock()
    {
        // 1. POJISTKA: Kontrola fyzického souboru na disku
        if (file_exists(storage_path('installed'))) {
            abort(403, 'CMS je již nainstalováno. Z bezpečnostních důvodů je instalátor uzamčen.');
        }

        // 2. POJISTKA: Dynamická kontrola běžící databáze
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('users')) {
                file_put_contents(storage_path('installed'), 'Automaticky uzamčeno dne: ' . now() . ' (Detekován funkční web)');
                abort(403, 'CMS je již nainstalováno. Z bezpečnostních důvodů je instalátor uzamčen.');
            }
        } catch (\Exception $e) {
            // Pokud připojení selže nebo databáze neexistuje, je to v pořádku.
        }
    }

    public function showDatabaseForm()
    {
        $this->checkInstallationLock(); // ZÁMEK PŘIDÁN
        return view('install.database');
    }

public function processDatabase(Request $request)
    {
        $this->checkInstallationLock();
        
        // 1. Validace - přidali jsme db_prefix (povolíme jen písmena, čísla a podtržítko)
        $validated = $request->validate([
            'db_host'     => 'required|string',
            'db_port'     => 'required|string',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'db_prefix'   => 'nullable|string|regex:/^[a-zA-Z0-9_]+$/',
        ]);

        // 2. Otestování spojení s databází včetně nového prefixu
        config(['database.connections.test_connection' => [
            'driver'    => 'mysql',
            'host'      => $validated['db_host'],
            'port'      => $validated['db_port'],
            'database'  => $validated['db_database'],
            'username'  => $validated['db_username'],
            'password'  => $validated['db_password'] ?? '',
            'prefix'    => $validated['db_prefix'] ?? '', // <-- PŘIDÁNO SEM
            'charset'   => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
        ]]);

        try {
            DB::connection('test_connection')->getPdo();
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['database' => 'Nepodařilo se připojit k databázi. Zkontrolujte prosím údaje. Chyba: ' . $e->getMessage()]);
        }

        // 3. Spojení je v pořádku -> zapíšeme údaje natvrdo do .env souboru
        $this->updateEnvFile('DB_CONNECTION', 'mysql');
        $this->updateEnvFile('DB_HOST', $validated['db_host']);
        $this->updateEnvFile('DB_PORT', $validated['db_port']);
        $this->updateEnvFile('DB_DATABASE', $validated['db_database']);
        $this->updateEnvFile('DB_USERNAME', $validated['db_username']);
        $this->updateEnvFile('DB_PASSWORD', $validated['db_password'] ?? '');
        $this->updateEnvFile('DB_PREFIX', $validated['db_prefix'] ?? ''); // <-- PŘIDÁNO SEM ZÁPIS PREFIXU

        return redirect()->route('install.migrations')->with('success', 'Databáze byla úspěšně připojena.');
    }

    public function runMigrations()
    {
        $this->checkInstallationLock(); // ZÁMEK PŘIDÁN
        
        try {
            Artisan::call('migrate', [
                '--force' => true
            ]);
            return redirect()->route('install.admin')->with('success', 'Databáze byla úspěšně vytvořena!');
        } catch (\Exception $e) {
            return redirect()->route('install.database')
                ->withErrors(['migrations' => 'Nastala chyba při vytváření tabulek: ' . $e->getMessage()]);
        }
    }

    public function showAdminForm()
    {
        $this->checkInstallationLock(); // ZÁMEK PŘIDÁN
        return view('install.admin');
    }

    public function processAdmin(Request $request)
    {
        $this->checkInstallationLock(); // ZÁMEK PŘIDÁN
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed', 
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Artisan::call('key:generate', ['--force' => true]);
        file_put_contents(storage_path('installed'), 'Nainstalováno dne: ' . now());

        return redirect('/')->with('success', 'Instalace byla úspěšně dokončena! Nyní se můžete přihlásit.');
    }

    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            if (file_exists(base_path('.env.example'))) {
                copy(base_path('.env.example'), $path);
            } else {
                touch($path); 
            }
        }

        $content = file_get_contents($path);

        if (str_contains($value, ' ')) {
            $value = '"' . $value . '"';
        }

        if (str_contains($content, "{$key}=")) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($path, $content);
    }
}