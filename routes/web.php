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
use App\Http\Controllers\InstallationController;
use App\Models\Page;
use App\Models\Article;
use App\Models\Menu;


/*
|--------------------------------------------------------------------------
| 1. INSTALÁTOR CMS (Nyní bezpečně jako první)
|--------------------------------------------------------------------------
*/
Route::prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallationController::class, 'showDatabaseForm'])->name('database');
    Route::post('/database', [InstallationController::class, 'processDatabase'])->name('database.process');
    Route::get('/migrations', [InstallationController::class, 'runMigrations'])->name('migrations');
    Route::get('/admin', [InstallationController::class, 'showAdminForm'])->name('admin');
    Route::post('/admin', [InstallationController::class, 'processAdmin'])->name('admin.process');
});


/*
|--------------------------------------------------------------------------
| 2. ZÁCHRANNÁ BRZDA PŘED INSTALACÍ (Chytré přesměrování)
|--------------------------------------------------------------------------
*/
// Pokud fyzický zámek na disku ještě neexistuje, chytíme jakoukoliv jinou 
// adresu (včetně domovské stránky) a okamžitě ji přesměrujeme do instalátoru.
if (!file_exists(storage_path('installed'))) {
    Route::fallback(fn() => redirect('/install'));
    
    // Příkaz 'return' způsobí, že Laravel tento soubor přestane číst.
    // Díky tomu se kód vůbec nedostane k routám níže a nespustí 
    // předčasně žádný PageController ani databázové dotazy!
    return; 
}


/*
|--------------------------------------------------------------------------
| 3. BĚŽNÝ CHOD WEBU (Načte se pouze tehdy, když je už nainstalováno)
|--------------------------------------------------------------------------
*/

// Auth routy
require __DIR__ . '/auth.php';

// Veřejné routy s lokalizací
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
        \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,
        \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
    ]
], function () {
    Route::get('/', [PageController::class, 'showBySlug'])->defaults('slug', 'home')->name('home');
    Route::get('/stranka/{slug}', [PageController::class, 'showBySlug'])->name('page.show');
    Route::get('/clanek/{slug}', [ArticleController::class, 'showBySlug'])->name('article.show');

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

// Administrace
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    Route::get('/images', [ImageManagerController::class, 'index'])->name('images.index');
    Route::post('/images', [ImageManagerController::class, 'store'])->name('images.store');
    Route::delete('/images/{id}', [ImageManagerController::class, 'destroy'])->name('images.destroy');

    Route::post('/menu/reorder', [MenuController::class, 'reorder'])->name('menu.reorder');

    Route::get('/', function () {
        // Vytáhneme 5 nejnovějších nezveřejněných stránek
        $unpublishedPages = Page::where('published', false)->latest()->take(5)->get();
        
        // Vytáhneme 5 nejnovějších nezveřejněných článků
        $unpublishedArticles = Article::where('published', false)->latest()->take(5)->get();
        
        // Vytáhneme skryté položky menu 
        // POZOR: Zkontroluj, jestli se tvůj sloupec pro skrytí jmenuje 'is_visible', 'active' nebo jinak!
        $hiddenMenuItems = Menu::where('published', false)->get(); 

        return view('admin.master', compact(
            'unpublishedPages', 
            'unpublishedArticles', 
            'hiddenMenuItems'
        ));
    })->name('dashboard');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('admin.store');

    Route::get('/layout-overrides', [LayoutOverrideController::class, 'index'])->name('admin.layout-overrides.index');
    Route::get('/layout-overrides/create', [LayoutOverrideController::class, 'create'])->name('admin.layout-overrides.create');
    Route::post('/layout-overrides', [LayoutOverrideController::class, 'store'])->name('admin.layout-overrides.store');
    Route::delete('/layout-overrides/{layoutOverride}', [LayoutOverrideController::class, 'destroy'])->name('admin.layout-overrides.destroy');

    Route::prefix('clanky')->name('article.')->group(function () {
        Route::get('/', [ArticleController::class, 'adminIndex'])->name('index');
        Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [ArticleController::class, 'togglePublished'])->name('toggle');

        Route::get('/vytvorit', [ArticleController::class, 'create'])->name('create');
        Route::post('/vytvorit', [ArticleController::class, 'store'])->name('store');
    });

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

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');        
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::get('/delete', [ProfileController::class, 'delete'])->name('delete');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/logout', [ProfileController::class, 'destroy'])->name('destroy');
    });

    Route::get('/toggle-editor', function () {
        session()->has('tinymce_enabled')
            ? session()->forget('tinymce_enabled')
            : session(['tinymce_enabled' => true]);

        return back();
    })->name('toggle.tinymce');
});

// Ostatní
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});