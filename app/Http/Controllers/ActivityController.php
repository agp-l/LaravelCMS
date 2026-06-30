<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::orderBy('name')->get();
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        $activity = new Activity();
        return view('admin.activities.form', compact('activity'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'show_child_name' => $request->has('show_child_name') ? 1 : 0,
            'show_kids_count' => $request->has('show_kids_count') ? 1 : 0,
            'show_child_info' => $request->has('show_child_info') ? 1 : 0,
            'show_note'       => $request->has('show_note') ? 1 : 0,
            'custom_field_required' => $request->has('custom_field_required') ? 1 : 0,
            'is_active'       => $request->has('is_active') ? 1 : 0, // NOVÉ: Uložení stavu
        ]);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price_per_hour'    => 'required|numeric|min:0',
            'price_per_day'     => 'required|numeric|min:0',
            'price_per_month'   => 'nullable|numeric|min:0',
            'color_theme'       => 'required|string|max:50',
            'icon'              => 'required|string|max:100',
            'max_capacity'      => 'required|integer|min:1',
            'booking_mode'      => 'required|string|in:individual,shared,both',
            'pricing_model'     => 'required|string|in:hourly,daily,monthly',
            'monthly_pass_mode' => 'required|string|in:all_days,single_day',
            'show_child_name'   => 'boolean',
            'show_kids_count'   => 'boolean',
            'show_child_info'   => 'boolean',
            'show_note'         => 'boolean',
            'custom_field_label'=> 'nullable|string|max:255',
            'custom_field_required' => 'boolean',
            'is_active'         => 'boolean',
            'days'              => 'required|array', 
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i',
            'start_time_2'      => 'nullable|date_format:H:i',
            'end_time_2'        => 'nullable|date_format:H:i',
        ], [
            'days.required' => 'Musíte vybrat alespoň jeden den v týdnu, kdy aktivita probíhá.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $activity = Activity::create($validated);

        foreach ($request->days as $day) {
            $activity->scheduleRules()->create([
                'day_of_week' => $day,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
            ]);

            if (!empty($request->start_time_2) && !empty($request->end_time_2)) {
                $activity->scheduleRules()->create([
                    'day_of_week' => $day,
                    'start_time'  => $request->start_time_2,
                    'end_time'    => $request->end_time_2,
                ]);
            }
        }

        return redirect()->route('admin.activities.index')->with('success', 'Aktivita a její rozvrh byly úspěšně vytvořeny.');
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);

        $request->merge([
            'show_child_name' => $request->has('show_child_name') ? 1 : 0,
            'show_kids_count' => $request->has('show_kids_count') ? 1 : 0,
            'show_child_info' => $request->has('show_child_info') ? 1 : 0,
            'show_note'       => $request->has('show_note') ? 1 : 0,
            'custom_field_required' => $request->has('custom_field_required') ? 1 : 0,
            'is_active'       => $request->has('is_active') ? 1 : 0, // NOVÉ: Uložení stavu
        ]);

        $validated = $request->validate([
            'name'              => 'required|string|max:255',
            'description'       => 'nullable|string',
            'price_per_hour'    => 'required|numeric|min:0',
            'price_per_day'     => 'required|numeric|min:0',
            'price_per_month'   => 'nullable|numeric|min:0',
            'color_theme'       => 'required|string|max:50',
            'icon'              => 'required|string|max:100',
            'max_capacity'      => 'required|integer|min:1',
            'booking_mode'      => 'required|string|in:individual,shared,both',
            'pricing_model'     => 'required|string|in:hourly,daily,monthly',
            'monthly_pass_mode' => 'required|string|in:all_days,single_day', 
            'show_child_name'   => 'boolean',
            'show_kids_count'   => 'boolean',
            'show_child_info'   => 'boolean',
            'show_note'         => 'boolean',
            'custom_field_label'=> 'nullable|string|max:255',
            'custom_field_required' => 'boolean',
            'is_active'         => 'boolean',
            'days'              => 'required|array',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i',
            'start_time_2'      => 'nullable|date_format:H:i',
            'end_time_2'        => 'nullable|date_format:H:i',
        ], [
            'days.required' => 'Musíte vybrat alespoň jeden den v týdnu, kdy aktivita probíhá.',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $activity->update($validated);
        $activity->scheduleRules()->delete();

        foreach ($request->days as $day) {
            $activity->scheduleRules()->create([
                'day_of_week' => $day,
                'start_time'  => $request->start_time,
                'end_time'    => $request->end_time,
            ]);

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

    public function edit(Activity $activity)
    {
        return view('admin.activities.form', compact('activity'));
    }

    public function destroy(Activity $activity)
    {
        $activity->delete();
        return redirect()->route('admin.activities.index')->with('success', 'Aktivita byla smazána.');
    }
}