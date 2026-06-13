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
        // 1. Získáme parametry z URL (pokud neexistují, nastavíme výchozí)
        $activityFilter = $request->query('activity_id');
        $sortBy = $request->query('sort_by', 'date_desc'); // Výchozí: od nejnovějších dat
        
        // 2. Základní dotaz (včetně relace na aktivitu)
        $query = Reservation::with('activity');

        // 3. Aplikace filtru aktivity (pokud admin nějakou vybral)
        if (!empty($activityFilter)) {
            $query->where('activity_id', $activityFilter);
        }

        // 4. Aplikace řazení
        switch ($sortBy) {
            case 'date_asc':
                $query->orderBy('date', 'asc'); // Od nejstarších termínů (blížících se)
                break;
            case 'date_desc':
                $query->orderBy('date', 'desc'); // Od nejnovějších
                break;
            case 'created_desc':
                $query->orderBy('created_at', 'desc'); // Kdo se přihlásil naposledy
                break;
            default:
                $query->orderBy('date', 'desc');
                break;
        }

        // 5. Spustíme dotaz a pošleme do pohledu i existující aktivity pro select box
        $reservations = $query->paginate(20)->withQueryString(); // withQueryString() drží filtr i při přechodu na další stránku
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
            'date'           => 'required|date',
            'activity_id'    => 'required|integer|exists:activities,id',
            'child_name'     => 'required|string|max:255',
            'kids_count'     => 'required|integer|min:1',
            'child_info'     => 'nullable|string|max:500', // PŘIDÁNO: věk dětí
            'parent_name'    => 'required|string|max:255',
            'contact'        => 'required|string|max:255',
            'note'           => 'nullable|string|max:1000', // PŘIDÁNO: poznámka
            'sharing_type'   => 'required|string',
            'pricing_model'  => 'required|string',
            'total_price'    => 'required|numeric|min:0',
            'payment_status' => 'required|string|in:pending,paid,cancelled',
            'slots'          => 'required|string', // Přichází jako text oddělený čárkou
        ]);

        // Převedeme text řetězec slotů zpět na čisté PHP pole pro databázi
        $slotsArray = array_map('trim', explode(',', $validated['slots']));

        $reservation->update([
            'date'           => $validated['date'],
            'activity_id'    => $validated['activity_id'],
            'child_name'     => $validated['child_name'],
            'kids_count'     => $validated['kids_count'],
            'child_info'     => $validated['child_info'] ?? '', // PŘIDÁNO
            'parent_name'    => $validated['parent_name'],
            'contact'        => $validated['contact'],
            'note'           => $validated['note'] ?? '', // PŘIDÁNO
            'sharing_type'   => $validated['sharing_type'],
            'pricing_model'  => $validated['pricing_model'],
            'total_price'    => $validated['total_price'],
            'payment_status' => $validated['payment_status'],
            'slots'          => $slotsArray,
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

        // Vezmeme začátek prvního bloku a konec posledního bloku
        $firstSlot = trim(explode('-', $slotsArray[0])[0]);
        $lastSlot = trim(explode('-', end($slotsArray))[1]);

        // Pro Google převedeme čas do UTC (odstraní problémy s letním časem)
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
        
        // Hlavička iCalendar souboru
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

        // Vytvoření bezpečného názvu souboru
        $filename = "rezervace_" . Str::slug($reservation->child_name) . "_{$date}.ics";

        // Odeslání souboru prohlížeči
        return response($icsContent)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }





}