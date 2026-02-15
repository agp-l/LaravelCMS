<?php
require __DIR__ . '/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MenuController;
use App\Models\Article;

Route::get('/admin/toggle-editor', function () {
    session()->has('tinymce_disabled')
        ? session()->forget('tinymce_disabled')
        : session(['tinymce_disabled' => true]);

    return back();
})->name('toggle.tinymce');


Route::get('/', [PageController::class, 'showBySlug'])->name('home')->defaults('slug', 'home');


Route::get('/clanky', function () {
    $articles = Article::where('published', true)->orderBy('created_at', 'desc')->get();
    return view('articles.index', compact('articles')); // bez lomítka na začátku!
})->name('index');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/clanek/{slug}', [ArticleController::class, 'showBySlug'])->name('article.show');
Route::get('/stranka/{slug}', [PageController::class, 'showBySlug'])->name('page.show');


Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Blogové články (dříve pages)
    Route::get('/admin/clanky', [ArticleController::class, 'adminIndex'])->name('article.index');
    Route::get('/admin/clanek/{id}/edit', [ArticleController::class, 'edit'])->name('article.edit');
    Route::put('/admin/clanek/{id}', [ArticleController::class, 'update'])->name('article.update');
    Route::delete('/admin/clanek/{id}', [ArticleController::class, 'destroy'])->name('article.destroy');
    Route::get('/admin/novy-clanek', [ArticleController::class, 'create'])->name('article.create');
    Route::post('/admin/novy-clanek', [ArticleController::class, 'store'])->name('article.store');
    Route::post('/admin/clanek/{id}/toggle', [ArticleController::class, 'togglePublished'])->name('article.toggle');

    // Statické stránky (nové pages)
    Route::get('/admin/stranky', [PageController::class, 'index'])->name('page.index');
    Route::get('/admin/stranka/{id}/edit', [PageController::class, 'edit'])->name('page.edit');
    Route::put('/admin/stranka/{id}', [PageController::class, 'update'])->name('page.update');
    Route::delete('/admin/stranka/{id}', [PageController::class, 'destroy'])->name('page.destroy');
    Route::get('/admin/nova-stranka', [PageController::class, 'create'])->name('page.create');
    Route::post('/admin/nova-stranka', [PageController::class, 'store'])->name('page.store');
    Route::post('/admin/stranka/{id}/toggle', [PageController::class, 'togglePublished'])->name('page.toggle');



    Route::get('/admin/menu', [MenuController::class, 'index'])->name('menu.index');
    Route::get('/admin/menu/create', [MenuController::class, 'create'])->name('menu.create');
    Route::post('/admin/menu', [MenuController::class, 'store'])->name('menu.store');
    Route::get('/admin/menu/{id}/edit', [MenuController::class, 'edit'])->name('menu.edit');
    Route::put('/admin/menu/{id}', [MenuController::class, 'update'])->name('menu.update');
    Route::delete('/admin/menu/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');




});
