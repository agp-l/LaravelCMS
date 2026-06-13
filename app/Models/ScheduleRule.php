<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleRule extends Model
{
    protected $fillable = ['day_of_week', 'date_override', 'start_time', 'end_time', 'activity_id', 'is_blocked'];

    protected $casts = [
        'is_blocked' => 'boolean',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}