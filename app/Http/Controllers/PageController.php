<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // 🔹 Veřejné zobrazení stránky podle slug (např. /o-nas)
    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
    
        return view('pages.page-detail', ['page' => $page]);
    }
    

    // 🔹 Veřejné zobrazení první stránky – (možná testovací metoda?)
    public function show()
    {
        $page = Page::first();

        if (!$page) {
            abort(404, 'Stránka nenalezena');
        }

        return view('pages.page-detail', ['page' => $page]);
    }

    // 🔹 Zobrazení stránky podle ID – např. z administrace jako náhled
    public function showById($id)
    {
        $page = Page::findOrFail($id);

        return view('pages.page-detail', ['page' => $page]);
    }

    // 🔸 ADMIN – přehled všech stránek v administraci
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();

        return view('admin.pages.index', compact('pages'));
    }

    // 🔸 ADMIN – vytvoření nové stránky (zobrazení formuláře)
    public function create()
    {
        return view('admin.pages.create');
    }

    // 🔸 ADMIN – uložení nové stránky
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
            'published' => 'nullable',
        ]);

        Page::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'] ?? '',
            'published' => $request->has('published'),
        ]);

        return redirect()->route('page.index')->with('success', 'Stránka byla vytvořena.');
    }

    // 🔸 ADMIN – editace stránky
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('admin.pages.edit', compact('page'));
    }

    // 🔸 ADMIN – aktualizace stránky
public function update(Request $request, $id)
{
    $page = Page::findOrFail($id);

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
        'content' => 'nullable|string',
        'published' => 'nullable',
    ]);

    // Obsah bereme z requestu, ale chráníme se před tím,
    // že se omylem uloží prázdné JSON bloky "[]"
    // (typicky když se přepne režim / nespustí JS / nejsou bloky).
    $content = $request->input('content', null);

    if ($content !== null) {
        $trim = trim($content);

        // Pojistka proti smazání obsahu na []
        if ($trim === '[]') {
            $content = $page->content;
        }
    } else {
        // Pokud content vůbec nepřišel, nebudeme ho přepisovat
        $content = $page->content;
    }

    $page->update([
        'title' => $validated['title'],
        'slug' => $validated['slug'],
        'content' => $content,
        'published' => $request->has('published'),
    ]);

    return redirect('/admin/stranky')->with('success', 'Stránka byla upravena.');
}

    // 🔸 ADMIN – smazání stránky
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect('/admin/stranky')->with('success', 'Stránka byla smazána.');
    }

    // 🔸 ADMIN – přepnutí stavu "published"
    public function togglePublished($id)
    {
        $page = Page::findOrFail($id);
        $page->published = !$page->published;
        $page->save();

        return redirect()->route('page.index')->with('success', 'Změněn stav zveřejnění stránky.');
    }


    public function preview($id)
    {
        $page = Page::findOrFail($id);

        return view('pages.page-detail', [
            'page' => $page,
            'preview' => true
        ]);
    }




}