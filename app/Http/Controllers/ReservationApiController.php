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

        // 1. Globální blokace celého dne
        $globalBlock = \App\Models\ScheduleRule::where('date_override', $dateStr)
            ->whereNull('activity_id')
            ->where('is_blocked', true)
            ->exists();

        if ($globalBlock) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        // 2. Načtení pravidel pro danou aktivitu
        $rules = \App\Models\ScheduleRule::where('activity_id', $activityId)
            ->where(function($query) use ($dateStr, $dayOfWeek) {
                $query->where('date_override', $dateStr)
                      ->orWhere(function($q) use ($dayOfWeek) {
                          $q->where('day_of_week', $dayOfWeek)->whereNull('date_override');
                      });
            })
            ->get();

        if ($rules->isEmpty() || $rules->first()->is_blocked) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        $rule = $rules->first();
        $ruleStart = intval(substr($rule->start_time, 0, 2));
        $ruleEnd = intval(substr($rule->end_time, 0, 2));

        // 3. Načtení existujících rezervací
        $reservations = \App\Models\Reservation::where('date', $dateStr)
            ->where('activity_id', $activityId)
            ->where('payment_status', '!=', 'cancelled')
            ->get();

        $responseSlots = [];
        $maxCapacity = 5;

        for ($hour = $ruleStart; $hour < $ruleEnd; $hour++) {
            
            $slotStart = sprintf('%02d:00', $hour);
            $slotEnd   = sprintf('%02d:00', $hour + 1);
            $slotLabel = "{$slotStart} - {$slotEnd}";

            // Filtrování rezervací patřících do tohoto slotu
            $slotReservations = $reservations->filter(function($res) use ($slotLabel) {
                // Pojistka: pokud slots v DB nejsou automaticky castované na pole, dekódujeme JSON ručně
                $slotsArray = is_array($res->slots) ? $res->slots : json_decode($res->slots, true);
                return is_array($slotsArray) && in_array($slotLabel, $slotsArray);
            });

            $status = 'FREE';
            $currentKidsCount = 0;
            $isPrivate = false;
            $childrenNames = [];

            foreach ($slotReservations as $res) {
                // Bezpečně uložíme jméno přihlášeného dítěte
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

        return response()->json([
            'date' => $dateStr,
            'slots' => $responseSlots,
            'is_blocked' => false
        ]);
    }
}