<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Přidáno pro kontrolu tabulek
use Illuminate\Support\Str; // Přidáno pro kontrolu názvu šablony

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Společný view composer pro všechny šablony
        view()->composer('*', function ($view) {
            
            // 1. ZÁCHRANNÁ SÍŤ: Defaultní hodnoty (zabrání pádu, pokud ještě není databáze)
            $view->with('menuTree', collect());
            $layout = config('view.default_layout', 'layouts.default.app');
            $view->with('layout', $layout);

            // 2. POJISTKA PRO INSTALACI: Pokud načítáme instalační stránky, tady skončíme
            if (Str::startsWith($view->getName(), 'install.')) {
                return;
            }

            // 3. REÁLNÝ KÓD: Pokus o načtení dat z databáze
            try {
                // A) Pokus o načtení MENU
                if (Schema::hasTable('menus')) {
                    $menus = Menu::where('published', true)
                        ->orderBy('order')
                        ->get();
                        
                    // Využijeme funkci buildMenuTree definovanou níže ve třídě
                    $menuTree = $this->buildMenuTree($menus);
                    $view->with('menuTree', $menuTree);
                }

                // B) Pokus o načtení LAYOUT OVERRIDES (výjimek v rozvržení)
                if (Schema::hasTable('layout_overrides')) {
                    $overrides = DB::table('layout_overrides')->get();
                    
                    foreach ($overrides as $override) {
                        $pattern = ltrim($override->path_pattern, '/');

                        if (request()->is($pattern) || request()->is('*/' . $pattern)) {
                            $view->with('layout', $override->layout);
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                // Pokud databáze nekomunikuje, chytí se chyba zde.
                // Web nespadne, protože díky bodu 1 už šablony dostaly prázdné menu a defaultní layout.
            }
        });

        // Jazykový přepínač
        View::composer(['partials.language-switch', 'mizzle.language-switch'], function ($view) {
            $languageLinks = [];

            foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties) {
                $languageLinks[] = [
                    'code' => $localeCode,
                    'label' => $properties['name'],
                    'url' => LaravelLocalization::getLocalizedURL($localeCode),
                    'flag' => "/flags/{$localeCode}.svg",
                ];
            }

            $view->with('languageLinks', $languageLinks);
        });

        // Tohle přidá Bootstrap 5 vzhled pro stránkování
        Paginator::useBootstrapFive();
    }

    /**
     * Pomocná funkce pro sestavení stromového menu
     */
    private function buildMenuTree($items, $parentId = null)
    {
        $branch = [];

        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                // Rekurzivní volání přes $this->
                $children = $this->buildMenuTree($items, $item->id);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }
}