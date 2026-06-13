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
use App\Http\Controllers\ReservationApiController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TravelDiaryController;

/*
|--------------------------------------------------------------------------
| 1. INSTALÁTOR CMS
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
| 2. ZÁCHRANNÁ BRZDA PŘED INSTALACÍ
|--------------------------------------------------------------------------
*/
if (!file_exists(storage_path('installed'))) {
    Route::fallback(fn() => redirect('/install'));
    return; 
}

/*
|--------------------------------------------------------------------------
| 3. BĚŽNÝ CHOD WEBU
|--------------------------------------------------------------------------
*/

// Auth routy
require __DIR__ . '/auth.php';

// API a nezávislé routy pro frontend
Route::get('/api/reservation/availability', [ReservationApiController::class, 'getAvailability'])->name('api.reservation.availability');
Route::get('/rezervace', [ReservationController::class, 'index'])->name('reservation.index');
Route::post('/rezervace', [ReservationController::class, 'store'])->name('reservation.store');
Route::get('/denik', [TravelDiaryController::class, 'index'])->name('diary.index');

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
    
    // Nástěnka
    Route::get('/', function () {
        $unpublishedPages = Page::where('published', false)->latest()->take(5)->get();
        $unpublishedArticles = Article::where('published', false)->latest()->take(5)->get();
        $hiddenMenuItems = Menu::where('published', false)->get(); 

        return view('admin.master', compact(
            'unpublishedPages', 
            'unpublishedArticles', 
            'hiddenMenuItems'
        ));
    })->name('dashboard');

    // Registrace dalších adminů
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('admin.register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('admin.store');

    // Obrázky
    Route::get('/images', [ImageManagerController::class, 'index'])->name('images.index');
    Route::post('/images', [ImageManagerController::class, 'store'])->name('images.store');
    Route::delete('/images/{id}', [ImageManagerController::class, 'destroy'])->name('images.destroy');

    // Menu
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
        Route::post('/reorder', [MenuController::class, 'reorder'])->name('reorder');
    });

    // Cestovní deník
    Route::prefix('denik')->name('diary.')->group(function () {
        Route::get('/', [TravelDiaryController::class, 'adminIndex'])->name('admin');
        Route::get('/vytvorit', [TravelDiaryController::class, 'create'])->name('create');
        Route::post('/', [TravelDiaryController::class, 'store'])->name('store');
        Route::get('/{diary}/upravit', [TravelDiaryController::class, 'edit'])->name('edit');
        Route::put('/{diary}', [TravelDiaryController::class, 'update'])->name('update');
        Route::delete('/{diary}', [TravelDiaryController::class, 'destroy'])->name('destroy');
    });

    // Layout Overrides
    Route::get('/layout-overrides', [LayoutOverrideController::class, 'index'])->name('admin.layout-overrides.index');
    Route::get('/layout-overrides/create', [LayoutOverrideController::class, 'create'])->name('admin.layout-overrides.create');
    Route::post('/layout-overrides', [LayoutOverrideController::class, 'store'])->name('admin.layout-overrides.store');
    Route::delete('/layout-overrides/{layoutOverride}', [LayoutOverrideController::class, 'destroy'])->name('admin.layout-overrides.destroy');

    // Články
    Route::prefix('clanky')->name('article.')->group(function () {
        Route::get('/', [ArticleController::class, 'adminIndex'])->name('index');
        Route::get('/vytvorit', [ArticleController::class, 'create'])->name('create');
        Route::post('/vytvorit', [ArticleController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [ArticleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ArticleController::class, 'update'])->name('update');
        Route::delete('/{id}', [ArticleController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [ArticleController::class, 'togglePublished'])->name('toggle');
    });

    // Stránky
    Route::prefix('stranky')->name('page.')->group(function () {
        Route::get('/', [PageController::class, 'index'])->name('index');
        Route::get('/vytvorit', [PageController::class, 'create'])->name('create');
        Route::post('/vytvorit', [PageController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [PageController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PageController::class, 'update'])->name('update');
        Route::delete('/{id}', [PageController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/toggle', [PageController::class, 'togglePublished'])->name('toggle');
        Route::get('/{id}/nahled', [PageController::class, 'preview'])->name('preview');
    });

    // Profil
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('show');        
        Route::get('/edit', [ProfileController::class, 'edit'])->name('edit');
        Route::get('/delete', [ProfileController::class, 'delete'])->name('delete');
        Route::patch('/update', [ProfileController::class, 'update'])->name('update');
        Route::delete('/logout', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // Správa aktivit (Rezervační systém)
    Route::prefix('aktivity')->name('admin.activities.')->group(function () {
        Route::get('/', [App\Http\Controllers\ActivityController::class, 'index'])->name('index');
        Route::get('/vytvorit', [App\Http\Controllers\ActivityController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\ActivityController::class, 'store'])->name('store');
        Route::get('/{activity}/upravit', [App\Http\Controllers\ActivityController::class, 'edit'])->name('edit');
        Route::put('/{activity}', [App\Http\Controllers\ActivityController::class, 'update'])->name('update');
        Route::delete('/{activity}', [App\Http\Controllers\ActivityController::class, 'destroy'])->name('destroy');
    });

    // Správa provedených rezervací účastníků
    Route::prefix('rezervace-seznam')->name('admin.reservations.')->group(function () {
        Route::get('/', [App\Http\Controllers\AdminReservationController::class, 'index'])->name('index');
        Route::get('/{id}/upravit', [App\Http\Controllers\AdminReservationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [App\Http\Controllers\AdminReservationController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [App\Http\Controllers\AdminReservationController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/{id}', [App\Http\Controllers\AdminReservationController::class, 'destroy'])->name('destroy');

        // NOVÉ ROUTY PRO KALENDÁŘ:
        Route::get('/{id}/google-calendar', [App\Http\Controllers\AdminReservationController::class, 'googleCalendar'])->name('google');
        Route::get('/{id}/ics', [App\Http\Controllers\AdminReservationController::class, 'downloadIcs'])->name('ics');
    });

     // Dispečink a mimořádné výluky (zablokování dnů a aktivit)
    Route::prefix('admin/blocks')->name('admin.blocks.')->group(function () {
        Route::get('/', [App\Http\Controllers\BlockController::class, 'index'])->name('index');
        Route::post('/day', [App\Http\Controllers\BlockController::class, 'storeDay'])->name('store_day');
        Route::delete('/day/{id}', [App\Http\Controllers\BlockController::class, 'destroyDay'])->name('destroy_day');
        Route::post('/activity/{id}', [App\Http\Controllers\BlockController::class, 'toggleActivity'])->name('toggle_activity');
    });

    
    // Přepínač editoru
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