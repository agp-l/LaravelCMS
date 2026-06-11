<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\PageHistory;
use Illuminate\Support\Facades\Blade;

class PageController extends Controller
{
    // Verejne zobrazeni stranky podle slug
    public function showBySlug($slug)
    {
        $query = Page::where('slug', $slug);
        
        // Pokud uzivatel NENI prihlaseny, vyzadujeme, aby byla stranka zverejnena
        if (!auth()->check()) {
            $query->where('published', true);
        }
        
        $page = $query->firstOrFail();
    
        $text = $page->content;

        // OPRAVENO: Odstranena uzaviraci zavorka ve str_contains
        if (str_contains($text, '[qr_platba')) {
            $text = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
                return \Illuminate\Support\Facades\Blade::render("<x-qr-payment {$matches[1]} />");
            }, $text);
        }

        $headerData = $this->extractHeaderData($text);
        $page->content = $text;

        return view('pages.page-detail', [
            'page' => $page,
            'headerData' => $headerData
        ]);
    }
    
    // Verejne zobrazeni prvni stranky (domovska stranka)
    public function show()
    {
        $query = Page::query();
        
        // Pokud uzivatel NENI prihlaseny, vyzadujeme, aby byla stranka zverejnena
        if (!auth()->check()) {
            $query->where('published', true);
        }
        
        $page = $query->first(); 
        
        if (!$page) {
            abort(404, 'Stranka nenalezena');
        }

        $text = $page->content;

        // OPRAVENO
        if (str_contains($text, '[qr_platba')) {
            $text = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
                return \Illuminate\Support\Facades\Blade::render("<x-qr-payment {$matches[1]} />");
            }, $text);
        }

        $headerData = $this->extractHeaderData($text);
        $page->content = $text;

        return view('pages.page-detail', [
            'page' => $page,
            'headerData' => $headerData
        ]);
    }

    // Zobrazeni stranky podle ID
    public function showById($id)
    {
        $page = Page::findOrFail($id);

        $text = $page->content;

        // OPRAVENO
        if (str_contains($text, '[qr_platba')) {
            $text = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
                return \Illuminate\Support\Facades\Blade::render("<x-qr-payment {$matches[1]} />");
            }, $text);
        }

        $headerData = $this->extractHeaderData($text);
        $page->content = $text;

        return view('pages.page-detail', [
            'page' => $page,
            'headerData' => $headerData
        ]);
    }

    // ADMIN - prehled vsech stranek v administraci
    public function index()
    {
        $pages = Page::orderBy('created_at', 'desc')->get();

        return view('admin.pages.index', compact('pages'));
    }

    // ADMIN - vytvoreni nove stranky (zobrazeni formulare)
    public function create()
    {
        return view('admin.pages.create');
    }

    // ADMIN - ulozeni nove stranky
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

        return redirect()->route('page.index')->with('success', 'Stranka byla vytvorena.');
    }

    // ADMIN - editace stranky
    public function edit($id)
    {
        $page = Page::findOrFail($id);

        $histories = \App\Models\PageHistory::where('page_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        if (request()->has('history_id')) {
            $history = \App\Models\PageHistory::where('page_id', $id)
                ->findOrFail(request()->query('history_id'));
            
            $page->title = $history->title;
            $page->slug = $history->slug;
            $page->content = $history->content;
            $page->published = $history->published;
            
            $page->is_history_preview = true;
            $page->history_date = $history->created_at;
        }

        return view('admin.pages.edit', compact('page', 'histories'));
    }

    // ADMIN - aktualizace stranky
    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

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

        $maxHistories = 15;
        $latestHistoryIds = \App\Models\PageHistory::where('page_id', $page->id)
            ->orderBy('created_at', 'desc')
            ->take($maxHistories)
            ->pluck('id');

        \App\Models\PageHistory::where('page_id', $page->id)
            ->whereNotIn('id', $latestHistoryIds)
            ->delete();

        if (!$page->published) {
            return redirect('/admin/stranky')->with('success', 'Stranka byla upravena (je skryta) a stara verze byla zalohovana.');
        }

        if ($page->slug === 'home') {
            return redirect()->route('home')->with('success', 'Stranka byla uspesne upravena a zverejnena.');
        }

        return redirect()->route('page.show', ['slug' => $page->slug])->with('success', 'Stranka byla uspesne upravena a zverejnena.');
    }

    // ADMIN - smazani stranky
    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return redirect('/admin/stranky')->with('success', 'Stranka byla smazana.');
    }

    // ADMIN - prepnuti stavu "published"
    public function togglePublished($id)
    {
        $page = Page::findOrFail($id);
        $page->published = !$page->published;
        $page->save();

        return redirect()->route('page.index')->with('success', 'Zmenen stav zverejneni stranky.');
    }

    // ADMIN - rychly nahled stranky z administrace
    public function preview($id)
    {
        $page = Page::findOrFail($id);

        $text = $page->content;

        // OPRAVENO
        if (str_contains($text, '[qr_platba')) {
            $text = preg_replace_callback('/\[qr_platba\s*(.*?)\]/', function ($matches) {
                return \Illuminate\Support\Facades\Blade::render("<x-qr-payment {$matches[1]} />");
            }, $text);
        }

        $headerData = $this->extractHeaderData($text);
        $page->content = $text;

        return view('pages.page-detail', [
            'page' => $page,
            'headerData' => $headerData,
            'preview' => true
        ]);
    }

    // Pomocna funkce pro extrakci hlavicky z textu
    private function extractHeaderData(&$content)
    {
        $headerData = null;
        
        if (preg_match('/\[hlavicka\s+(.*?)\]/s', $content, $matches)) {
            $attrString = $matches[1];
            
            preg_match_all('/(\w+)="([^"]*)"/s', $attrString, $attrMatches);
            $headerData = [];
            foreach ($attrMatches[1] as $index => $key) {
                $headerData[$key] = $attrMatches[2][$index];
            }
            
            $content = str_replace($matches[0], '', $content);
        }
        
        return $headerData;
    }
}