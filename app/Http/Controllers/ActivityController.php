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

public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'price_per_day'  => 'required|numeric|min:0',
            'color_theme'    => 'required|string|max:50',
            'icon'           => 'required|string|max:100',
            // Validační pravidla pro dny a časy
            'days'           => 'required|array', 
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i',
        ]);

        // Vytvoříme aktivitu
        $activity = Activity::create($validated);

        // Zapíšeme dny a časy do propojené tabulky schedule_rules
        foreach ($request->days as $day) {
            $activity->scheduleRules()->create([
                'day_of_week' => $day,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
            ]);
        }

        return redirect()->route('admin.activities.index')->with('success', 'Aktivita a její rozvrh byly úspěšně vytvořeny.');
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'price_per_day'  => 'required|numeric|min:0',
            'color_theme'    => 'required|string|max:50',
            'icon'           => 'required|string|max:100',
            // Validační pravidla pro dny a časy
            'days'           => 'required|array',
            'start_time'     => 'required|date_format:H:i',
            'end_time'       => 'required|date_format:H:i',
            'start_time_2' => 'nullable|date_format:H:i',
            'end_time_2'   => 'nullable|date_format:H:i',
        ]);

        // Aktualizujeme data aktivity (název, barva, ceny...)
        $activity->update($validated);

        // Nejprve smažeme stará pravidla rozvrhu, abychom je mohli nahradit novými
        $activity->scheduleRules()->delete();

        // Zapíšeme nově naklikané dny a časy do tabulky schedule_rules
        foreach ($request->days as $day) {
            // 1. Uložíme dopolední blok
            $activity->scheduleRules()->create([
                'day_of_week' => $day,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
            ]);

            // 2. Pokud existuje i druhý blok, uložíme ho pro stejný den jako další řádek!
            if (!empty($request->start_time_2) && !empty($request->end_time_2)) {
                $activity->scheduleRules()->create([
                    'day_of_week' => $day,
                    'start_time'  => $request->start_time_2,
                    'end_time'    => $request->end_time_2,
                ]);
            }
        }
        
        return redirect()->route('admin.activities.index')->with('success', 'Aktivita a její rozvrh byly úspěšně upraveny.');
    }

    // Formulář pro úpravu aktivity
    public function edit(Activity $activity)
    {
        return view('admin.activities.form', compact('activity'));
    }


    // Smazání aktivity
    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Aktivita byla smazána.');
    }
}