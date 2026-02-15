<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $allMenus = Menu::orderBy('order')->get();
        $menus = $this->buildMenuTree($allMenus);
    
        return view('admin.menus.index', compact('menus'));
    }


    private function buildMenuTree($items, $parentId = null, $level = 0)
    {
        $branch = [];
    
        foreach ($items as $item) {
            if ($item->parent_id == $parentId) {
                $item->level = $level; // Tady se nastavuje úroveň odsazení
                $branch[] = $item;
    
                // Rekurzivně přidáme děti
                $children = $this->buildMenuTree($items, $item->id, $level + 1);
                $branch = array_merge($branch, $children);
            }
        }
    
        return $branch;
    }


    public function create()
    {
        $menus = Menu::orderBy('order')->get(); // pro dropdown
        return view('admin.menus.create', compact('menus'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string',
            'url' => 'nullable|string|max:255',
            'published' => 'boolean',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menus,id',
        ]);
    
        // Generování URL, pokud není zadáno
        if (empty($validated['url']) && in_array($validated['type'], ['page', 'article'])) {
            $slug = \Str::slug($validated['label']);
            $validated['url'] = $validated['type'] === 'page'
                ? '/stranka/' . $slug
                : '/clanek/' . $slug;
        }
    
        Menu::create($validated);
    
        return redirect()->route('menu.index')->with('success', 'Odkaz byl přidán.');
    }
    


    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $menus = Menu::where('id', '!=', $id)->orderBy('order')->get(); // ostatní položky kromě sebe
        return view('admin.menus.edit', compact('menu', 'menus'));
    }
    
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
    
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'type' => 'required|string',
            'url' => 'nullable|string|max:255',
            'published' => 'boolean',
            'order' => 'nullable|integer',
            'parent_id' => 'nullable|exists:menus,id',
        ]);
    
        // Doplnění URL, pokud není zadaná
        if (empty($validated['url']) && in_array($validated['type'], ['page', 'article'])) {
            $slug = \Str::slug($validated['label']);
            $validated['url'] = $validated['type'] === 'page'
                ? '/stranka/' . $slug
                : '/clanek/' . $slug;
        }
    
        $menu->update($validated);
    
        return redirect()->route('menu.index')->with('success', 'Odkaz byl upraven.');
    }
    
    
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return redirect()->route('menu.index')->with('success', 'Odkaz byl smazán.');
    }

}
