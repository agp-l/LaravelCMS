<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminRevenueController extends Controller
{
    public function index()
    {
        Carbon::setLocale('cs'); // České názvy měsíců
        
        // Načteme aktivní aktivity s jejich pravidly
        $activities = Activity::with('scheduleRules')->where('is_active', true)->get();

        $potentialData = [];
        foreach ($activities as $act) {
            // Zjistíme, kolik unikátních dnů v týdnu aktivita běží podle rozvrhu
            $daysPerWeek = $act->scheduleRules->whereNull('date_override')->pluck('day_of_week')->unique()->count();
            if ($daysPerWeek == 0) $daysPerWeek = 1; 

            // Průměrný počet nabízených dnů v měsíci (dny v týdnu x 4 týdny)
            $daysPerMonth = $daysPerWeek * 1;

            // Spočítáme výchozí hodiny z rozvrhu aktivity jako doporučený základ
            $defaultHoursPerDay = 0;
            $blocks = $act->scheduleRules->whereNull('date_override')->unique(function($item) {
                return $item->start_time . '-' . $item->end_time;
            });
            foreach ($blocks as $block) {
                $start = Carbon::parse($block->start_time);
                $end = Carbon::parse($block->end_time);
                $defaultHoursPerDay += $start->diffInHours($end);
            }
            
            // Pokud aktivita nemá pevné hodiny, nastavíme výchozí 1 hodinu dle zadání
            if ($defaultHoursPerDay == 0) $defaultHoursPerDay = 1;

            $potentialData[] = (object) [
                'id' => $act->id,
                'name' => $act->name,
                'color' => $act->color_theme ?? '#059669',
                'icon' => $act->icon ?? 'fa-solid fa-puzzle-piece',
                'pricing_model' => $act->pricing_model,
                'price_per_hour' => $act->price_per_hour ?? 0,
                'price_per_day' => $act->price_per_day ?? 0,
                'price_per_month' => $act->price_per_month ?? 0,
                'days_per_month' => $daysPerMonth,
                'default_hours' => $defaultHoursPerDay,
            ];
        }

        // --- REÁLNÁ DATA Z OBJEDNÁVEK ---
        $reservations = Reservation::whereIn('payment_status', ['paid', 'pending'])
            ->orderBy('date', 'desc')
            ->get();

        $monthlyStats = [];
        foreach ($reservations as $res) {
            $monthYearKey = Carbon::parse($res->date)->format('Y-m');
            $monthName = Carbon::parse($res->date)->translatedFormat('F Y');
            
            if (!isset($monthlyStats[$monthYearKey])) {
                $monthlyStats[$monthYearKey] = [
                    'label' => mb_convert_case($monthName, MB_CASE_TITLE, "UTF-8"),
                    'paid' => 0,
                    'pending' => 0,
                    'count' => 0
                ];
            }
            
            // Sčítáme finální vypočítanou cenu, kterou systém uložil při rezervaci
            if ($res->payment_status === 'paid') {
                $monthlyStats[$monthYearKey]['paid'] += $res->total_price;
            } else {
                $monthlyStats[$monthYearKey]['pending'] += $res->total_price;
            }
            $monthlyStats[$monthYearKey]['count']++;
        }

        return view('admin.revenue.index', compact('potentialData', 'monthlyStats'));
    }
}