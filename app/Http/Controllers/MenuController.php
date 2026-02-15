<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


class MenuController extends Controller
{
    public function index()
    {
        $allMenus = Menu::orderBy('order')->get();
        $menus = $this->buildMenuTree($allMenus);

        // Přidáme lokalizované URL
        foreach ($menus as $menu) {
            //$menu->url = LaravelLocalization::localizeURL($menu->url);
            $menu->url = $this->localizedUrlSafe($menu->url);
        }

        return view('admin.menus.index', compact('menus'));
    }

    public function list()
    {
        $allMenus = Menu::orderBy('order')->get();
        $menus = $this->buildMenuTree($allMenus);

        // Přidáme lokalizované URL
        foreach ($menus as $menu) {
            $menu->url = LaravelLocalization::localizeURL($menu->url);
        }

        return view('admin.menus.list', ['menus' => $menus]);
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

    public function reorder(Request $request)
    {
        $data = json_decode($request->input('order_data'), true);

        foreach ($data as $item) {
            $id = (int) $item['id'];
            $order = (int) $item['order'];
            $parentId = $item['parent_id'] ?? null;
            $parentId = $parentId === null || $parentId === 'null' ? null : (int) $parentId;

            Menu::where('id', $id)->update([
                'parent_id' => $parentId,
                'order' => $order,
            ]);
        }

        return redirect()->route('menu.index')->with('success', 'Pořadí bylo uloženo.');
    }




    private function localizedUrlSafe(string $url): string
    {
        // Pokud URL už obsahuje jazykový prefix, nech ji být
        $supported = array_keys(config('laravellocalization.supportedLocales'));

        foreach ($supported as $locale) {
            if (preg_match("#^/{$locale}(/|$)#", $url)) {
                return $url;
            }
        }

        return LaravelLocalization::localizeURL($url);
    }   
}
