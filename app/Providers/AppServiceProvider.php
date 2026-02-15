<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


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

        // Společný view composer
        view()->composer('*', function ($view) {
            // 💡 MENU – důležité zachovat
            $menus = Menu::where('published', true)
                ->orderBy('order')
                ->get();
            $menuTree = buildMenuTree($menus);
            $view->with('menuTree', $menuTree);

            // 💡 AKTUÁLNÍ cesta a route
            $routeName = Route::currentRouteName();
            $requestPath = request()->path(); // např. cs/clanek/xyz

            // 💡 Výchozí layout (můžeš číst z .env nebo configu)
            $layout = config('view.default_layout', 'layouts.default.app');

            // 💡 NEJPRVE: zkus najít výjimku v databázi
            $overrides = DB::table('layout_overrides')->get();

            foreach ($overrides as $override) {
                if (request()->is($override->path_pattern)) {
                    $layout = $override->layout;
                    break;
                }
            }

            // 💡 Potom můžeš přidat hardcodované výjimky (přepisuje DB, pokud chceš)
            /*
            if ($routeName === 'page.show') {
                $layout = 'layouts.mizzle.app';
            }

            if ($routeName === 'article.publicIndex.show') {
                $layout = 'layouts.default.app';
            }

            if (request()->is('cs/clanek/manifest-svobody-uceni')) {
                $layout = 'layouts.mizzle.app';
            }
                */

            // 💡 Předání do všech views
            $view->with('layout', $layout);
        });



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


    function buildMenuTree($items, $parentId = null)
    {
        $branch = [];

        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $children = buildMenuTree($items, $item->id);
                if ($children) {
                    $item->children = $children;
                }
                $branch[] = $item;
            }
        }

        return $branch;
    }

}

