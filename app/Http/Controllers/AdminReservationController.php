<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Activity;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    // Výpis všech provedených rezervací
    public function index()
    {
        // Načteme rezervace seřazené od nejnovějších a propojíme je s tabulkou aktivit (Eager Loading)
        $reservations = Reservation::with('activity')->orderBy('date', 'desc')->paginate(20);

        return view('admin.reservations.index', compact('reservations'));
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
            'parent_name'    => 'required|string|max:255',
            'contact'        => 'required|string|max:255',
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
            'parent_name'    => $validated['parent_name'],
            'contact'        => $validated['contact'],
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
}