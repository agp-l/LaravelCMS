<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class LayoutOverrideController extends Controller
{
    // Zobrazí stránku s nastavením tématu
    public function index()
    {
        // Najdeme, jaké téma je aktuálně nastaveno (pokud žádné, vrátí null)
        $currentTheme = DB::table('layout_overrides')->where('path_pattern', '*')->value('layout');

        return view('admin.layout-overrides.index', compact('currentTheme'));
    }

    // Uloží nové globální téma
    public function store(Request $request)
    {
        $request->validate([
            'layout' => 'required|string|max:255',
        ]);

        // updateOrInsert najde záznam s hvězdičkou a přepíše ho. Pokud neexistuje, vytvoří ho.
        DB::table('layout_overrides')->updateOrInsert(
            ['path_pattern' => '*'],
            ['layout' => $request->input('layout')]
        );

        // Okamžitě smažeme mezipaměť šablon, aby se nový vzhled propsal na web
        Artisan::call('view:clear');

        return redirect()->route('admin.layout-overrides.index')->with('success', 'Globální vzhled webu byl úspěšně změněn!');
    }
}