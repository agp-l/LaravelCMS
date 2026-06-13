<?php

namespace App\Http\Controllers;

use App\Models\TimelinePost;
use Illuminate\Http\Request;

class TravelDiaryController extends Controller
{
    // Zobrazení deníku na webu (frontend)
    public function index()
    {
        $posts = TimelinePost::orderBy('created_at', 'desc')->paginate(15);
        return view('diary.index', compact('posts'));
    }

    // Administrace: Výpis všech záznamů
    public function adminIndex()
    {
        $posts = TimelinePost::orderBy('created_at', 'desc')->paginate(20);
        return view('diary.admin_index', compact('posts'));
    }

    // Administrace: Formulář pro nový záznam
    public function create()
    {
        $post = new TimelinePost();
        return view('diary.form', compact('post'));
    }

    // Administrace: Uložení nového záznamu
    public function store(Request $request)
    {
        $validated = $request->validate([
            'created_at' => 'required|date',
            'icon_class' => 'required|string|max:255',
            'content'    => 'required|string',
            'map_url'    => 'nullable|string'
        ]);

        if (empty($validated['map_url'])) {
            $validated['map_url'] = 'none';
        }

        TimelinePost::create($validated);

        return redirect()->route('diary.admin')->with('success', 'Záznam v deníku byl úspěšně vytvořen.');
    }

    // Administrace: Formulář pro úpravu
    public function edit(TimelinePost $diary)
    {
        return view('diary.form', ['post' => $diary]);
    }

    // Administrace: Uložení úprav
    public function update(Request $request, TimelinePost $diary)
    {
        $validated = $request->validate([
            'created_at' => 'required|date',
            'icon_class' => 'required|string|max:255',
            'content'    => 'required|string',
            'map_url'    => 'nullable|string'
        ]);

        if (empty($validated['map_url'])) {
            $validated['map_url'] = 'none';
        }

        $diary->update($validated);

        return redirect()->route('diary.admin')->with('success', 'Záznam byl úspěšně upraven.');
    }

    // Administrace: Smazání záznamu
    public function destroy(TimelinePost $diary)
    {
        $diary->delete();
        return redirect()->route('diary.admin')->with('success', 'Záznam byl smazán.');
    }
}