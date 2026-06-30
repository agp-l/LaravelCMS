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

        // --- HLAVNÍ PŘEPÍNAČ SOUBĚHU AKTIVIT ---
        // false = lektor je jen jeden, aktivity se nesmí překrývat
        // true  = lektorů je více, aktivity mohou probíhat ve stejný čas
        $allowOverlappingActivities = false; 

        $activity = \App\Models\Activity::find($activityId);
        
        if (!$activity || !$activity->is_active) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        $maxCapacity = $activity->max_capacity ?? 5;

        // 1. Globální blokace celého dne
        $globalBlock = \App\Models\ScheduleRule::where('date_override', $dateStr)
            ->whereNull('activity_id')
            ->where('is_blocked', true)
            ->exists();

        if ($globalBlock) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        // 2. Načtení rozvrhu pro aktuální aktivitu
        $rules = \App\Models\ScheduleRule::where('activity_id', $activityId)
            ->where(function($query) use ($dateStr, $dayOfWeek) {
                $query->where('date_override', $dateStr)
                      ->orWhere(function($q) use ($dayOfWeek) {
                          $q->where('day_of_week', $dayOfWeek)->whereNull('date_override');
                      });
            })
            ->orderBy('start_time', 'asc')
            ->get();

        if ($rules->isEmpty() || $rules->first()->is_blocked) {
            return response()->json(['date' => $dateStr, 'slots' => [], 'is_blocked' => true]);
        }

        // 3. Načtení ÚPLNĚ VŠECH rezervací pro tento den (napříč všemi aktivitami)
        $allReservations = \App\Models\Reservation::where('payment_status', '!=', 'cancelled')
            ->where(function($q) use ($dateStr) {
                $q->where('date', $dateStr)
                  ->whereNull('date_end');
                
                $q->orWhere(function($sub) use ($dateStr) {
                    $sub->whereNotNull('date_end')
                        ->where('date', '<=', $dateStr)
                        ->where('date_end', '>=', $dateStr);
                });
            })
            ->get();

        // Filtr pro dny v týdnu (kvůli paušálům)
        $validReservations = $allReservations->filter(function($res) use ($dayOfWeek) {
            if ($res->date_end) {
                $days = $res->recurring_days;
                if (is_string($days)) {
                    $days = json_decode($days, true);
                }
                if (is_array($days)) {
                    return in_array($dayOfWeek, $days);
                }
                return false; 
            }
            return true;
        });

        // Rozdělíme rezervace na ty, co patří k aktuální aktivitě, a na ty ostatní
        $currentActivityReservations = $validReservations->where('activity_id', $activityId);
        $otherActivityReservations = $validReservations->where('activity_id', '!=', $activityId);

        $responseSlots = [];

        foreach ($rules as $rule) {
            $ruleStart = intval(substr($rule->start_time, 0, 2));
            $ruleEnd = intval(substr($rule->end_time, 0, 2));

            for ($hour = $ruleStart; $hour < $ruleEnd; $hour++) {
                
                $slotStart = sprintf('%02d:00', $hour);
                $slotEnd   = sprintf('%02d:00', $hour + 1);
                $slotLabel = "{$slotStart} - {$slotEnd}";

                // A) Kontrola, zda v tento čas neučíš už jinou aktivitu
                $isInstructorBusy = false;
                if (!$allowOverlappingActivities) {
                    $conflict = $otherActivityReservations->filter(function($res) use ($slotLabel) {
                        $slotsArray = is_array($res->slots) ? $res->slots : json_decode($res->slots, true);
                        return is_array($slotsArray) && in_array($slotLabel, $slotsArray);
                    });
                    
                    if ($conflict->isNotEmpty()) {
                        $isInstructorBusy = true;
                    }
                }

                // B) Logika kapacity pro AKTUÁLNÍ aktivitu
                $slotReservations = $currentActivityReservations->filter(function($res) use ($slotLabel) {
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

                // Pokud jsi obsazený jinde, nebo je tady plno/soukromá rezervace -> FULL
                if ($isInstructorBusy || $isPrivate || $currentKidsCount >= $maxCapacity) {
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
            'slots' => $responseSlots,
            'is_blocked' => false
        ]);
    }
}