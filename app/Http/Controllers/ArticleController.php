<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class ArticleController extends Controller
{
    public function showBySlug(Request $request, $slug)

    {
        if (!$slug) {
            abort(404);
        }
    
        $article = Article::where('slug', $slug)->firstOrFail();
    
        $selectedCategory = $request->query('category');
    
        $articlesQuery = Article::where('published', true);
    
        if ($selectedCategory) {
            $articlesQuery->where('category', $selectedCategory);
        }
    
        $sideArticles = $articlesQuery->orderBy('created_at', 'desc')->take(10)->get();
    
        $categories = Article::select('category')->distinct()->pluck('category')->filter();
    
        return view('articles.article-detail', [
            'article' => $article,
            'sideArticles' => $sideArticles,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }
    


    public function adminIndex(Request $request)
    {
        $articles = Article::query();

        if ($request->filled('category')) {
            $articles->where('category', $request->category);
        }

        $articles = $articles->orderBy('created_at', 'desc')->get();

        return view('admin.articles.index', ['articles' => $articles]);
    }



    public function create()
    {
        return view('admin.articles.create');
    }

    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('admin.articles.edit', ['article' => $article]);
    }




    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles',
            'content' => 'nullable|string',
            'published' => 'nullable',
            'category' => 'nullable|string|max:100',
            'perex' => 'nullable|string',
            'image' => 'nullable|image|max:8192',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . $imageFile->getClientOriginalName();

            $fullPath = public_path('img/blog/full/' . $imageName);
            $thumbPath = public_path('img/blog/thumbs/' . $imageName);

            // Ulož originál
            $imageFile->move(public_path('img/blog/full'), $imageName);

            // Vytvoř thumbnail
            $imageManager = ImageManager::withDriver(Driver::class);
            $imageManager->read($fullPath)
                ->cover(800, 533)
                ->save($thumbPath);
        }

        Article::create([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? \Str::slug($validated['title']),
            'content' => $validated['content'] ?? '',
            'published' => $request->has('published'),
            'category' => $validated['category'] ?? null,
            'perex' => $validated['perex'] ?? '',
            'image' => $imageName,
        ]);

        return redirect()->route('article.index')->with('success', 'Článek byl vytvořen.');
    }



    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:articles,slug,' . $article->id,
            'content' => 'nullable|string',
            'published' => 'nullable',
            'category' => 'nullable|string|max:100',
            'perex' => 'nullable|string',
            'image' => 'nullable|image|max:8192',
        ]);

        $imageName = $article->image;

        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $imageName = time() . '_' . $imageFile->getClientOriginalName();

            $fullPath = public_path('img/blog/full/' . $imageName);
            $thumbPath = public_path('img/blog/thumbs/' . $imageName);

            $imageFile->move(public_path('img/blog/full'), $imageName);

            $imageManager = ImageManager::withDriver(Driver::class);
            $imageManager->read($fullPath)
                ->cover(800, 533)
                ->save($thumbPath);
        }

        $article->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'] ?? \Str::slug($validated['title']),
            'content' => $validated['content'] ?? '',
            'published' => $request->has('published'),
            'category' => $validated['category'] ?? null,
            'perex' => $validated['perex'] ?? '',
            'image' => $imageName,
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


    public function publicIndex(Request $request)
    {
        $selectedCategory = $request->query('category');

        $query = Article::where('published', true);

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $articles = $query->orderBy('created_at', 'desc')->paginate(9);
        $categories = Article::select('category')->distinct()->pluck('category')->filter();

        return view('articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
        ]);
    }


    public function publicCategory($category)
    {
        $articles = Article::where('published', true)
            ->where('category', $category)
            ->orderBy('created_at', 'desc')
            ->paginate(9);

        $categories = Article::select('category')->distinct()->pluck('category')->filter();

        return view('articles.index', [
            'articles' => $articles,
            'categories' => $categories,
            'selectedCategory' => $category,
        ]);
    }










}
