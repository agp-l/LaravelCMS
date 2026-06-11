<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 
use Illuminate\Support\Str; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot()
    {
        // Společný view composer pro všechny šablony
        view()->composer('*', function ($view) {
            
            // STATICKÁ PAMĚŤ (TADY JE TA KOUZELNÁ OPRAVA)
            // Zabráníme tomu, aby se databáze ptala na stejnou věc pro každý @include
            static $sharedData = null;

            if ($sharedData === null) {
                
                // 1. ZÁCHRANNÁ SÍŤ: Defaultní hodnoty
                $sharedData = [
                    'menuTree' => collect(),
                    'layout'   => config('view.default_layout', 'layouts.default.app')
                ];

                // 2. POJISTKA PRO INSTALACI
                if (!Str::startsWith($view->getName(), 'install.')) {
                    
                    // 3. REÁLNÝ KÓD: Pokus o načtení dat z databáze
                    try {
                        // A) Pokus o načtení MENU
                        if (Schema::hasTable('menus')) {
                            $menus = Menu::where('published', true)
                                ->orderBy('order')
                                ->get();
                                
                            $sharedData['menuTree'] = $this->buildMenuTree($menus);
                        }

                         // B) Načtení GLOBÁLNÍHO vzhledu celého webu z existující tabulky
                        if (Schema::hasTable('layout_overrides')) {
                            // Hledáme záznam s cestou '*', který nyní funguje jako globální nastavení
                            $activeTheme = DB::table('layout_overrides')
                                ->where('path_pattern', '*')
                                ->value('layout');
                            
                            // Pokud jsme ho našli, přepíšeme jím ten výchozí
                            if ($activeTheme) {
                                $sharedData['layout'] = $activeTheme;
                            }
                        }
                    } catch (\Exception $e) {
                        // Tiché zachycení chyby - použijí se defaultní hodnoty v $sharedData
                    }
                }
            }

            // Pošleme data z naší uzamčené paměti do aktuální šablony
            $view->with('menuTree', $sharedData['menuTree']);
            $view->with('layout', $sharedData['layout']);
        });

        // Jazykový přepínač
        View::composer(['default.language-switch', 'mizzle.language-switch'], function ($view) {
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

        // Bootstrap 5 vzhled pro stránkování
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