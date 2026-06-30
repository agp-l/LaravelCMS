<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'date',
        'slots',
        'child_name',
        'kids_count',
        'child_info',
        'parent_name',
        'contact',
        'note',
        'custom_field_value',
        'sharing_type',
        'pricing_model',
        'total_price',
        'payment_status',
        'date_end',
        'recurring_days',
        'activity_id'
    ];

    protected $casts = [
        'slots' => 'array',
        'recurring_days' => 'array', // TOTO JE KLÍČOVÁ OPRAVA
        'date' => 'date',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}