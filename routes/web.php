<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ImageManagerController;
use App\Http\Controllers\LayoutOverrideController;




// Auth routy
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Veřejné routy s lokalizací (např. /cs/stranka/home)
|--------------------------------------------------------------------------
*/
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,

    ]
], function () {
    // Domovská stránka
    Route::get('/', [PageController::class, 'showBySlug'])->defaults('slug', 'home')->name('home');

    // Veřejné stránky a články podle slugu
    Route::get('/stranka/{slug}', [PageController::class, 'showBySlug'])->name('page.show');
    Route::get('/clanek/{slug}', [ArticleController::class, 'showBySlug'])->name('article.show');

    // Veřejný výpis článků a kategorií
    Route::prefix('clanky')->name('article.')->group(function () {
        Route::get('/', [ArticleController::class, 'publicIndex'])->name('publicIndex');
        Route::get('/kategorie/{category}', [ArticleController::class, 'publicCategory'])->name('byCategory');
    });

    Route::get('/galerie/all', [ImageManagerController::class, 'showAll'])->name('gallery.all');
    Route::get('/galerie/{group}', [ImageManagerController::class, 'showGroup'])->name('gallery.group');


    Route::get('/projekty', function () {
        return view('projects.index');
    })->name('projects.index');
    
});





/*
|--------------------------------------------------------------------------
| Správa Administračních routy (pouze pro přihlášené)
| Administrace (pouze pro přihlášené)
|--------------------------------------------------------------------------
*/


Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Images
    |--------------------------------------------------------------------------
    */

    Route::get('/images', [ImageManagerController::class, 'index'])->name('images.index');
    Route::post('/images', [ImageManagerController::class, 'store'])->name('images.store');
    Route::delete('/images/{id}', [ImageManagerController::class, 'destroy'])->name('images.destroy');




    Route::post('/menu/reorder', [MenuController::class, 'reorder'])->name('menu.reorder');


    /*
    |--------------------------------------------------------------------------
    | Admin dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/', fn() => view('admin.master'))->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('admin.store');



    /*
    |--------------------------------------------------------------------------
    | Layout override přehled
    |--------------------------------------------------------------------------
    */

    Route::get('/layout-overrides', [LayoutOverrideController::class, 'index'])->name('admin.layout-overrides.index');
    Route::get('/layout-overrides/create', [LayoutOverrideController::class, 'create'])->name('admin.layout-overrides.create');
    Route::post('/layout-overrides', [LayoutOverrideController::class, 'store'])->name('admin.layout-overrides.store');
    Route::delete('/layout-overrides/{layoutOverride}', [LayoutOverrideController::class, 'destroy'])->name('admin.layout-overrides.destroy');

    // Zkrácený zápis
    //Route::resource('layout-overrides', \App\Http\Controllers\Admin\LayoutOverrideController::class)->except(['edit', 'update', 'show']);



    /*
    |--------------------------------------------------------------------------
    | Správa článků
    |--------------------------------------------------------------------------
    */
    Route::prefix('clanky')->name('article.')->group(function () {
        Route::get('/', [ArticleController::class, 'adminIndex'])->name('index');
        Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [ArticleController::class, 'togglePublished'])->name('toggle');

        Route::get('/vytvorit', [ArticleController::class, 'create'])->name('create');
        Route::post('/vytvorit', [ArticleController::class, 'store'])->name('store');
    });

    /*
    |--------------------------------------------------------------------------
    | Správa statických stránek
    |--------------------------------------------------------------------------
    */
    Route::prefix('stranky')->name('page.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PageController::class, 'update'])->name('update');
        Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [PageController::class, 'togglePublished'])->name('toggle');
        Route::get('/{id}/nahled', [PageController::class, 'preview'])->name('preview');

        Route::get('/vytvorit', [PageController::class, 'create'])->name('create');
        Route::post('/vytvorit', [PageController::class, 'store'])->name('store');
    });

    /*
    |--------------------------------------------------------------------------
    | Správa menu
    |--------------------------------------------------------------------------
    */
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', [MenuController::class, 'index'])->name('index');
        Route::get('/index', [MenuController::class, 'index'])->name('index');
        Route::get('/list', [MenuController::class, 'list'])->name('list');
        Route::get('/create', [MenuController::class, 'create'])->name('create');
        Route::post('/create', [MenuController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [MenuController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MenuController::class, 'update'])->name('update');
        Route::delete('/{id}', [MenuController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/nahled', [MenuController::class, 'preview'])->name('preview');
    });

    /*
    |--------------------------------------------------------------------------
    | Profil uživatele
    |--------------------------------------------------------------------------
    */
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::get('/delete', [ProfileController::class, 'delete'])->name('delete');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/logout', [ProfileController::class, 'destroy'])->name('destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Přepínač TinyMCE editoru
    |--------------------------------------------------------------------------
    */
    Route::get('/toggle-editor', function () {
        session()->has('tinymce_disabled')
            ? session()->forget('tinymce_disabled')
            : session(['tinymce_disabled' => true]);

        return back();
    })->name('toggle.tinymce');
});



Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});