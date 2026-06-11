<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\PageHistory;
use Illuminate\Support\Facades\Blade;

class PageController extends Controller
{
    // 🔹 Veřejné zobrazení stránky podle slug (např. /o-nas nebo tvůj kontakt)
    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('published', true)
                    ->firstOrFail();
    
        // ✨ PŘIDÁNO: Zpracování shortcodu pro QR platbu před odesláním do šablony
        $page->content = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
            return Blade::render("<x-qr-payment {$matches[1]} />");
        }, $page->content);

        return view('pages.page-detail', ['page' => $page]);
    }
    

    // 🔹 Veřejné zobrazení první stránky – (možná testovací metoda?)
    public function show()
    {
        // Najde první stránku, ale pouze z těch, které jsou veřejné
        $page = Page::where('published', true)->first(); 
        if (!$page) {
            abort(404, 'Stránka nenalezena');
        }

        // ✨ PŘIDÁNO: Zpracování shortcodu pro QR platbu
            // Najde [qr_platba] i s jakýmikoli vnitřními parametry
        $page->content = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
            // $matches[1] obsahuje surové parametry, např.: vs="2000" msg="Test"
            // Laravel je automaticky napáruje na proměnné v konstruktoru komponenty!
            return Blade::render("<x-qr-payment {$matches[1]} />");
        }, $page->content);


        return view('pages.page-detail', ['page' => $page]);
    }

    // 🔹 Zobrazení stránky podle ID – např. z administrace jako náhled
    public function showById($id)
    {
        $page = Page::findOrFail($id);

        // ✨ PŘIDÁNO: Zpracování shortcodu pro QR platbu
            // Najde [qr_platba] i s jakýmikoli vnitřními parametry
        $page->content = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
            // $matches[1] obsahuje surové parametry, např.: vs="2000" msg="Test"
            // Laravel je automaticky napáruje na proměnné v konstruktoru komponenty!
            return Blade::render("<x-qr-payment {$matches[1]} />");
        }, $page->content);

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

        // Načteme všechny historické verze pro tuto stránku seřazené od nejnovější po nejstarší
        $histories = \App\Models\PageHistory::where('page_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Pokud uživatel v adrese poslal konkrétní verzi (např. ?history_id=5)
        if (request()->has('history_id')) {
            $history = \App\Models\PageHistory::where('page_id', $id)
                ->findOrFail(request()->query('history_id'));
            
            // Dočasně přepíšeme data v objektu $page daty ze staré zálohy
            $page->title = $history->title;
            $page->slug = $history->slug;
            $page->content = $history->content;
            $page->published = $history->published;
            
            // Přidáme pomocné značky, abychom v šabloně poznali, že prohlížíme historii
            $page->is_history_preview = true;
            $page->history_date = $history->created_at;
        }

        return view('admin.pages.edit', compact('page', 'histories'));
    }


    // 🔸 ADMIN – aktualizace stránky
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        // ZÁLOHA: Než stránku přepíšeme novými daty, uložíme její současný stav do historie
        PageHistory::create([
            'page_id'   => $page->id,
            'title'     => $page->title,
            'slug'      => $page->slug,
            'content'   => $page->content,
            'published' => $page->published,
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'published' => 'nullable',
        ]);

        $content = $request->input('content', null);

        if ($content !== null) {
            $trim = trim($content);

            if ($trim === '[]') {
                $content = $page->content;
            }
        } else {
            $content = $page->content;
        }

        $page->update([
            'title' => $validated['title'],
            'slug' => $validated['slug'],
            'content' => $content,
            'published' => $request->has('published'),
        ]);

        return redirect('/admin/stranky')->with('success', 'Stránka byla upravena a stará verze byla zálohována.');
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

    // 🔸 ADMIN – rychlý náhled stránky z administrace
    public function preview($id)
    {
        $page = Page::findOrFail($id);

        // ✨ PŘIDÁNO: Zpracování shortcodu pro QR platbu, aby byl vidět i v náhledu
        if (str_contains($page->content, '[qr_platba]')) {
            $page->content = str_replace('[qr_platba]', Blade::render('<x-qr-payment />'), $page->content);
        }

        return view('pages.page-detail', [
            'page' => $page,
            'preview' => true
        ]);
    }
}