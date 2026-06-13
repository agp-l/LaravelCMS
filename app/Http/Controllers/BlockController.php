<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRule;
use App\Models\Activity;
use Carbon\Carbon;

class BlockController extends Controller
{
    // Zobrazení celého dispečinku
    public function index()
    {
        // Vytáhne všechny výjimky, kde je datum a is_blocked je true, seřazené od nejnovějších
        $blockedDays = ScheduleRule::where('is_blocked', true)
                                   ->whereNotNull('date_override')
                                   ->orderBy('date_override', 'asc')
                                   ->get();

        $activities = Activity::orderBy('name', 'asc')->get();

        return view('admin.blocks.index', compact('blockedDays', 'activities'));
    }

    // Uložení globálního dne volna
    public function storeDay(Request $request)
    {
        $request->validate(['blocked_date' => 'required|date']);

        // Spočítáme den v týdnu z vybraného data, kdyby ho DB vyžadovala
        $date = \Carbon\Carbon::parse($request->blocked_date);

        // Zapíše do tabulky pravidlo s výchozími časy, aby byla splněna integrita DB.
        // Není vyplněné activity_id, což pro systém značí globální výluku.
        ScheduleRule::create([
            'date_override' => $request->blocked_date,
            'is_blocked'    => true,
            'day_of_week'   => $date->dayOfWeek,
            'start_time'    => '00:00:00',
            'end_time'      => '23:59:59',
        ]);

        return back()->with('success', 'Datum bylo úspěšně zablokováno pro všechny rezervace.');
    }

    // Zrušení dne volna
    public function destroyDay($id)
    {
        ScheduleRule::findOrFail($id)->delete();
        return back()->with('success', 'Blokace dne byla zrušena.');
    }

    // Přepínání viditelnosti Aktivity (is_active)
    public function toggleActivity($id)
    {
        $activity = Activity::findOrFail($id);
        $activity->update(['is_active' => !$activity->is_active]);

        $stav = $activity->is_active ? 'aktivována' : 'pozastavena';
        return back()->with('success', "Aktivita {$activity->name} byla {$stav}.");
    }
}