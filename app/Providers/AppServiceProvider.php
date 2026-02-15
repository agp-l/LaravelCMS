<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Menu;


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
        view()->composer('*', function ($view) {
            $menus = Menu::where('published', true)
                        ->orderBy('order')
                        ->get();
    
            $menuTree = buildMenuTree($menus);
            $view->with('menuTree', $menuTree);
        });
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
