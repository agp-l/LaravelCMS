<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'icon', 'price_per_hour', 'color_theme', 'is_active'];

    public function scheduleRules()
    {
        return $this->hasMany(ScheduleRule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Vytvoří hezký štítek pro zobrazení na kartě
    public function getScheduleTagAttribute()
    {
        $rules = $this->scheduleRules()->whereNull('date_override')->get();
        if ($rules->isEmpty()) {
            return "Dle dohody";
        }

        $daysMap = [0 => 'NE', 1 => 'PO', 2 => 'ÚT', 3 => 'ST', 4 => 'ČT', 5 => 'PÁ', 6 => 'SO'];
        $days = $rules->pluck('day_of_week')->unique()->map(fn($d) => $daysMap[$d])->toArray();
        
        $dayString = implode(', ', $days);
        if (count($days) > 1) {
             // Pokud jsou dny např. SO a NE, napíše SO - NE
             $dayString = reset($days) . ' - ' . end($days);
        }

        $firstRule = $rules->first();
        $start = substr($firstRule->start_time, 0, 5);
        $end = substr($firstRule->end_time, 0, 5);

        return $dayString . ': ' . $start . ' - ' . $end;
    }
}