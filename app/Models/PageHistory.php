<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageHistory extends Model
{
    protected $table = 'page_histories';

    protected $fillable = [
        'page_id',
        'title',
        'slug',
        'content',
        'published',
    ];

    // Propojení zpět na původní stránku
    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}