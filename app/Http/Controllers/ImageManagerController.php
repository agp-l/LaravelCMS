<?php

namespace App\Http\Controllers;

use App\Models\ImageManager;
use Illuminate\Http\Request;

class ImageManagerController extends Controller
{
    public function index(Request $request)
    {
        $collection = $request->input('collection');
        $group = $request->input('group');
        $sort = $request->input('sort', 'asc');
    
        // připravíme dotaz
        $query = ImageManager::whereHas('media', function ($q) use ($collection) {
            if ($collection) {
                $q->where('collection_name', $collection);
            }
        })
        ->with(['media' => function ($q) use ($collection) {
            if ($collection) {
                $q->where('collection_name', $collection);
            }
        }]);
    
        // filtrujeme podle skupiny (jen pokud má smysl)
        if (!empty($group)) {
            $query->where('group', $group);
        }
    
        // třídění podle title jen pro galerie
        if ($collection === 'gallery') {
            $query->orderBy('title', $sort);
        }
    
        $images = $query->paginate(20);
    
    
        $groups = [];
    
        if ($collection) {
            $groups = ImageManager::whereHas('media', function ($q) use ($collection) {
                $q->where('collection_name', $collection);
            })
            ->distinct()
            ->pluck('group');
        }
    
        return view('image-manager.index', compact('images', 'collection', 'group', 'sort', 'groups'));
    }
    
    
    
    

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240',
            'collection' => 'required|in:gallery,pages',
            'title' => 'nullable|string|max:255',
            'group' => 'required|string|max:255',
            'perex' => 'nullable|string|max:1000',
        ]);
    
        $collection = $request->input('collection');
    
        $image = ImageManager::create([
            'title' => $request->input('title'),
            'group' => $request->input('group'),
            'perex' => $request->input('perex'),
        ]);
    
        $image->addMedia($request->file('image'))
              ->toMediaCollection($collection);
    
        return redirect()->back()->with('success', 'Obrázek byl úspěšně nahrán!');
    }
    
    

    public function destroy($id)
    {
        $image = ImageManager::findOrFail($id);
        $image->clearMediaCollection('images');
        $image->delete();

        return redirect()->back()->with('success', 'Obrázek smazán!');
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
{
    $this->addMediaConversion('thumb')
        ->width(400)
        ->height(300)
        ->sharpen(10);
}


public function showGroup(Request $request, $group)
{
    $images = ImageManager::where('group', $group)
        ->whereHas('media', function ($q) {
            $q->where('collection_name', 'gallery');
        })
        ->with(['media' => function ($q) {
            $q->where('collection_name', 'gallery');
        }])
        ->orderBy('title')
        ->paginate(12);

    return view('gallery.group', compact('images', 'group'));
}

public function showAll()
{
    $images = ImageManager::whereHas('media', function ($q) {
        $q->where('collection_name', 'gallery');
    })
    ->with(['media' => function ($q) {
        $q->where('collection_name', 'gallery');
    }])
    ->orderBy('group')
    ->paginate(18);

    $groups = ImageManager::whereHas('media', function ($q) {
        $q->where('collection_name', 'gallery');
    })
    ->distinct()
    ->pluck('group');

    return view('gallery.all', compact('images', 'groups'));
}

}
