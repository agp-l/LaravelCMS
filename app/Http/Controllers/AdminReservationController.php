<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminReservationController extends Controller
{
    // Výpis všech provedených rezervací
    public function index(Request $request)
    {
        $activityFilter = $request->query('activity_id');
        $sortBy = $request->query('sort_by', 'date_desc');
        
        $query = Reservation::with('activity');

        if (!empty($activityFilter)) {
            $query->where('activity_id', $activityFilter);
        }

        switch ($sortBy) {
            case 'date_asc':
                $query->orderBy('date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('date', 'desc');
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('date', 'desc');
                break;
        }

        $reservations = $query->paginate(20)->withQueryString();
        $activities = Activity::orderBy('name')->get();

        return view('admin.reservations.index', compact('reservations', 'activities', 'activityFilter', 'sortBy'));
    }

    // Formulář pro úpravu existující rezervace
    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        $activities = Activity::orderBy('name')->get();

        return view('admin.reservations.form', compact('reservation', 'activities'));
    }

    // Uložení změn v rezervaci
public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'date'               => 'required|date',
            'date_end'           => 'nullable|date',
            'recurring_days'     => 'nullable|array', 
            'activity_id'        => 'required|integer|exists:activities,id',
            'child_name'         => 'nullable|string|max:255', 
            'kids_count'         => 'nullable|integer|min:1',  
            'child_info'         => 'nullable|string|max:500',
            'parent_name'        => 'required|string|max:255',
            'contact'            => 'required|string|max:255',
            'note'               => 'nullable|string|max:1000',
            'custom_field_value' => 'nullable|string|max:1000',
            'sharing_type'       => 'required|string',
            'pricing_model'      => 'required|string',
            'total_price'        => 'required|numeric|min:0',
            'payment_status'     => 'required|string|in:pending,paid,cancelled',
            'slots'              => 'required|array', 
        ]);

        $reservation->update([
            'date'               => $validated['date'],
            'date_end'           => $validated['date_end'] ?? null,
            // OPRAVA: Nepoužíváme json_encode, předáme čisté pole a model si to přežvýká sám
            'recurring_days'     => $validated['recurring_days'] ?? null,
            'activity_id'        => $validated['activity_id'],
            'child_name'         => $validated['child_name'] ?? 'Nezadáno',
            'kids_count'         => $validated['kids_count'] ?? 1,
            'child_info'         => $validated['child_info'] ?? '',
            'parent_name'        => $validated['parent_name'],
            'contact'            => $validated['contact'],
            'note'               => $validated['note'] ?? '',
            'custom_field_value' => $validated['custom_field_value'] ?? null,
            'sharing_type'       => $validated['sharing_type'],
            'pricing_model'      => $validated['pricing_model'],
            'total_price'        => $validated['total_price'],
            'payment_status'     => $validated['payment_status'],
            'slots'              => $validated['slots'], 
        ]);

        return redirect()->route('admin.reservations.index')->with('success', 'Rezervace byla úspěšně upravena.');
    }

    // Rychlé přepnutí stavu platby přímo z tabulky
    public function toggleStatus($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->payment_status = $reservation->payment_status === 'paid' ? 'pending' : 'paid';
        $reservation->save();

        return back()->with('success', 'Stav platby byl změněn.');
    }

    // Odstranění rezervace
    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();

        return redirect()->route('admin.reservations.index')->with('success', 'Rezervace byla smazána.');
    }

    // 1. Generování odkazu pro Google Kalendář
    public function googleCalendar($id)
    {
        $reservation = Reservation::with('activity')->findOrFail($id);
        $activityName = $reservation->activity ? $reservation->activity->name : 'Rezervace';
        $date = \Carbon\Carbon::parse($reservation->date)->format('Y-m-d');
        
        $slotsArray = is_array($reservation->slots) ? $reservation->slots : json_decode($reservation->slots, true) ?? [];
        
        if (empty($slotsArray)) {
            return back()->with('error', 'Chybí hodiny rezervace.');
        }

        $firstSlot = trim(explode('-', $slotsArray[0])[0]);
        $lastSlot = trim(explode('-', end($slotsArray))[1]);

        $dtStart = \Carbon\Carbon::parse($date . ' ' . $firstSlot, 'Europe/Prague')->setTimezone('UTC')->format('Ymd\THis\Z');
        $dtEnd = \Carbon\Carbon::parse($date . ' ' . $lastSlot, 'Europe/Prague')->setTimezone('UTC')->format('Ymd\THis\Z');

        $title = urlencode($activityName . ' - ' . $reservation->child_name);
        $details = urlencode("Dítě: {$reservation->child_name} ({$reservation->kids_count} d.)\nRodič: {$reservation->parent_name}\nKontakt: {$reservation->contact}\nPoznámka: {$reservation->note}");
        
        $url = "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&dates={$dtStart}/{$dtEnd}&details={$details}";

        return redirect()->away($url);
    }

    // 2. Generování univerzálního .ics souboru
    public function downloadIcs($id)
    {
        $reservation = Reservation::with('activity')->findOrFail($id);
        $activityName = $reservation->activity ? $reservation->activity->name : 'Rezervace';
        $date = \Carbon\Carbon::parse($reservation->date)->format('Ymd');
        
        $slotsArray = is_array($reservation->slots) ? $reservation->slots : json_decode($reservation->slots, true) ?? [];
        
        $icsContent = "BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//Dobrodruzi//NONSGML v1.0//EN\r\n";
        
        foreach ($slotsArray as $slot) {
            $times = explode('-', $slot);
            if (count($times) == 2) {
                $startTime = str_replace(':', '', trim($times[0])) . '00';
                $endTime = str_replace(':', '', trim($times[1])) . '00';
                
                $dtStart = $date . 'T' . $startTime;
                $dtEnd = $date . 'T' . $endTime;
                
                $description = "Dítě: {$reservation->child_name}\\nRodič: {$reservation->parent_name}\\nKontakt: {$reservation->contact}\\nPoznámka: {$reservation->note}";
                
                $icsContent .= "BEGIN:VEVENT\r\n";
                $icsContent .= "UID:" . uniqid() . "@dobrodruzi.cz\r\n";
                $icsContent .= "DTSTAMP:" . gmdate('Ymd\THis\Z') . "\r\n";
                $icsContent .= "DTSTART;TZID=Europe/Prague:" . $dtStart . "\r\n";
                $icsContent .= "DTEND;TZID=Europe/Prague:" . $dtEnd . "\r\n";
                $icsContent .= "SUMMARY:" . $activityName . " - " . $reservation->child_name . "\r\n";
                $icsContent .= "DESCRIPTION:" . str_replace("\n", "\\n", $description) . "\r\n";
                $icsContent .= "END:VEVENT\r\n";
            }
        }
        $icsContent .= "END:VCALENDAR\r\n";

        $filename = "rezervace_" . Str::slug($reservation->child_name) . "_{$date}.ics";

        return response($icsContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}