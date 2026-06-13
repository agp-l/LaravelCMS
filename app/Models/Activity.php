<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'price_per_hour', 'price_per_day', 'color_theme', 'is_active'];

    public function scheduleRules()
    {
        return $this->hasMany(ScheduleRule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getScheduleTagAttribute()
    {
        // 1. Pokud nemá žádná pravidla, vrátíme výchozí text
        if ($this->scheduleRules->isEmpty()) {
            return 'Dle domluvy';
        }

        // 2. Vytáhneme si jen unikátní dny z pravidel (bez výjimek)
        $daysNumbers = $this->scheduleRules->whereNull('date_override')
                                           ->pluck('day_of_week')
                                           ->unique()
                                           ->toArray();

        if (empty($daysNumbers)) {
            return 'Dle domluvy';
        }

        // 3. Seřadíme dny od Pondělí do Neděle 
        // (Databáze bere Neděli jako 0, pro řazení ji dočasně chápeme jako 7)
        usort($daysNumbers, function($a, $b) {
            $sortA = ($a == 0) ? 7 : $a;
            $sortB = ($b == 0) ? 7 : $b;
            return $sortA <=> $sortB;
        });

        // 4. Přeložíme čísla na české zkratky
        $map = [1 => 'PO', 2 => 'ÚT', 3 => 'ST', 4 => 'ČT', 5 => 'PÁ', 6 => 'SO', 0 => 'NE', 7 => 'NE'];
        $daysLabels = array_map(function($day) use ($map) {
            return $map[$day];
        }, $daysNumbers);

        // 5. Spojíme je čárkou (výsledek: "PO, ST, PÁ")
        $daysString = implode(', ', $daysLabels);

        // 6. Zpracujeme VŠECHNY unikátní časové bloky (včetně polední pauzy)
        $timeBlocks = $this->scheduleRules->whereNull('date_override')->unique(function($item) {
            return $item->start_time . '-' . $item->end_time;
        })->values();

        if ($timeBlocks->isNotEmpty()) {
            $timeStrings = [];
            foreach($timeBlocks as $block) {
                $timeStrings[] = \Carbon\Carbon::parse($block->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($block->end_time)->format('H:i');
            }
            
            $finalTime = implode(', ', $timeStrings);
            return $daysString . ': ' . $finalTime;
        }

        return $daysString;
    }
}