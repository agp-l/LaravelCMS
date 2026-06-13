<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduleRule;
use App\Models\Reservation;
use Carbon\Carbon;

class ReservationApiController extends Controller
{
    public function getAvailability(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'activity_id' => 'required|integer'
        ]);

        $dateStr = $request->input('date');
        $activityId = $request->input('activity_id');
        $date = \Carbon\Carbon::parse($dateStr);
        $dayOfWeek = $date->dayOfWeek;

        // --- NOVÁ KONTROLA: Je aktivita vůbec aktivní? ---
        $activity = \App\Models\Activity::find($activityId);
        if (!$activity || !$activity->is_active) {
            // Pokud aktivita neexistuje nebo je pozastavená, vrátíme prázdný kalendář
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }
        // --- KONEC NOVÉ KONTROLY ---

        // 1. Globální blokace celého dne
        $globalBlock = \App\Models\ScheduleRule::where('date_override', $dateStr)
            ->whereNull('activity_id')
            ->where('is_blocked', true)
            ->exists();

        if ($globalBlock) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        // 2. Načtení VŠECH pravidel pro danou aktivitu a seřazení podle času
        $rules = \App\Models\ScheduleRule::where('activity_id', $activityId)
            ->where(function($query) use ($dateStr, $dayOfWeek) {
                $query->where('date_override', $dateStr)
                      ->orWhere(function($q) use ($dayOfWeek) {
                          $q->where('day_of_week', $dayOfWeek)->whereNull('date_override');
                      });
            })
            ->orderBy('start_time', 'asc') // Důležité: seřadí bloky chronologicky (dopoledne, pak odpoledne)
            ->get();

        if ($rules->isEmpty() || $rules->first()->is_blocked) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        // 3. Načtení existujících rezervací
        $reservations = \App\Models\Reservation::where('date', $dateStr)
            ->where('activity_id', $activityId)
            ->where('payment_status', '!=', 'cancelled')
            ->get();

        $responseSlots = [];
        $maxCapacity = 5;

        // 4. Cyklus přes VŠECHNY nalezené časové bloky pro daný den
        foreach ($rules as $rule) {
            $ruleStart = intval(substr($rule->start_time, 0, 2));
            $ruleEnd = intval(substr($rule->end_time, 0, 2));

            // Vnitřní cyklus pro vygenerování jednotlivých hodin uvnitř konkrétního bloku
            for ($hour = $ruleStart; $hour < $ruleEnd; $hour++) {
                
                $slotStart = sprintf('%02d:00', $hour);
                $slotEnd   = sprintf('%02d:00', $hour + 1);
                $slotLabel = "{$slotStart} - {$slotEnd}";

                // Filtrování rezervací patřících do tohoto slotu
                $slotReservations = $reservations->filter(function($res) use ($slotLabel) {
                    $slotsArray = is_array($res->slots) ? $res->slots : json_decode($res->slots, true);
                    return is_array($slotsArray) && in_array($slotLabel, $slotsArray);
                });

                $status = 'FREE';
                $currentKidsCount = 0;
                $isPrivate = false;
                $childrenNames = [];

                foreach ($slotReservations as $res) {
                    if (!empty($res->child_name)) {
                        $childrenNames[] = $res->child_name;
                    }

                    if ($res->sharing_type === 'Individuální čas') {
                        $isPrivate = true;
                    } else {
                        $status = 'SHARED';
                        $currentKidsCount += $res->kids_count;
                    }
                }

                if ($isPrivate || $currentKidsCount >= $maxCapacity) {
                    $status = 'FULL';
                }

                $responseSlots[] = [
                    'slot' => $slotLabel,
                    'status' => $status,
                    'current_kids' => $currentKidsCount,
                    'max_capacity' => $maxCapacity,
                    'children_names' => $childrenNames
                ];
            }
        }

        return response()->json([
            'date' => $dateStr,
            'slots' => $responseSlots, // Tady se už pošlou spojené hodiny z obou bloků
            'is_blocked' => false
        ]);
    }
}