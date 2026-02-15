<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function showBySlug($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();
        return view('articles.article-detail', ['article' => $article]);
    }

    public function adminIndex()
    {
        $articles = Article::orderBy('created_at', 'desc')->get();
        return view('admin.articles.index', ['articles' => $articles]);
    }


    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages',
            'content' => 'nullable|string',
            'published' => 'nullable',
        ]);

        Article::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? \Str::slug($validated['title']),
            'content' => $validated['content'] ?? '',
            'published' => $request->has('published'),
        ]);

        return redirect()->route('article.index')->with('success', 'Článek byl vytvořen.');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', ['article' => $article]);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'content' => 'nullable|string',
            'published' => 'nullable',
        ]);

        $article->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? \Str::slug($validated['title']),
            'content' => $validated['content'] ?? '',
            'published' => $request->has('published'),
        ]);

        return redirect()->route('article.index')->with('success', 'Článek byl upraven.');
    }

    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('article.index')->with('success', 'Článek byl smazán.');
    }

    public function togglePublished($id)
    {
        $article = Article::findOrFail($id);
        $article->published = !$article->published;
        $article->save();

        return redirect()->route('article.index')->with('success', 'Změna viditelnosti uložena.');
    }
}
