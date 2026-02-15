<?php
// app/Http/Controllers/LayoutOverrideController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LayoutOverride;
use Illuminate\Http\Request;

class LayoutOverrideController extends Controller
{
    public function index()
    {
        $overrides = LayoutOverride::all();
        return view('admin.layout-overrides.index', compact('overrides'));
    }

    public function create()
    {
        return view('admin.layout-overrides.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'path_pattern' => 'required|string',
            'layout' => 'required|string',
        ]);

        LayoutOverride::create($request->only(['path_pattern', 'layout']));

        return redirect()->route('admin.layout-overrides.index')->with('success', 'Výjimka byla uložena.');
    }

    public function destroy(LayoutOverride $layoutOverride)
    {
        $layoutOverride->delete();

        return redirect()->route('admin.layout-overrides.index')->with('success', 'Výjimka byla smazána.');
    }
}

