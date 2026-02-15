<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;


class PageController extends Controller
{
    public function show()
    {
        $page = Page::first();

        if (!$page) {
            abort(404, 'Stránka nenalezena');
        }

        return view('page', ['page' => $page]);
    }


    public function create()
    {
        return view('admin.pages.create');

    }
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

    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }


    public function adminIndex()
    {
        $pages = Page::orderBy('created_at', 'desc')->get(); // všechny stránky, i nezveřejněné

        return view('admin.pages', ['pages' => $pages]);
    }


    public function edit($id)
    {
        $page = Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));

    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'published' => 'nullable',
        ]);
        

        $page->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $validated['content'] ?? '',
            'published' => $request->has('published'),
        ]);
        
        

        return redirect('/admin/stranky')->with('success', 'Stránka byla upravena.');
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect('/admin/stranky')->with('success', 'Stránka byla smazána.');
    }



    public function showById($id)
    {
        $page = Page::findOrFail($id); // najde stránku nebo vyhodí 404

        return view('pages/page-detail', ['page' => $page]);
    }

    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
    
        return view('pages/page-detail', ['page' => $page]);
    }


    public function togglePublished($id)
{
    $page = Page::findOrFail($id);
    $page->published = !$page->published;
    $page->save();

    return redirect()->route('page.index')->with('success', 'Změněn stav zveřejnění stránky.');
}

    

}


