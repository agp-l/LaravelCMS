<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'date', 'slots', 'child_name', 'kids_count', 'child_info',
        'parent_name', 'contact', 'note', 'pricing_model',
        'sharing_type', 'total_price', 'payment_status', 'activity_id'
    ];

    protected $casts = [
        'slots' => 'array', // Laravel automaticky prevede JSON text z databáze na PHP pole
        'date' => 'date',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}