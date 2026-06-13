<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    // Výpis všech aktivit v administraci
    public function index()
    {
        $activities = Activity::orderBy('name')->get();
        return view('admin.activities.index', compact('activities'));
    }

    // Formulář pro novou aktivitu
    public function create()
    {
        $activity = new Activity();
        return view('admin.activities.form', compact('activity'));
    }

    // Uložení nové aktivity
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'color_theme'    => 'required|string|max:50',
            'icon'           => 'required|string|max:100',
            'schedule_tag'   => 'required|string|max:255',
        ]);

        Activity::create($validated);

        return redirect()->route('admin.activities.index')->with('success', 'Aktivita byla úspěšně vytvořena.');
    }

    // Formulář pro úpravu aktivity
    public function edit(Activity $activity)
    {
        return view('admin.activities.form', compact('activity'));
    }

    // Uložení úprav aktivity
    public function update(Request $request, Activity $activity)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'color_theme'    => 'required|string|max:50',
            'icon'           => 'required|string|max:100',
            'schedule_tag'   => 'required|string|max:255',
        ]);

        $activity->update($validated);

        return redirect()->route('admin.activities.index')->with('success', 'Aktivita byla úspěšně upravena.');
    }

    // Smazání aktivity
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Aktivita byla smazána.');
    }
}