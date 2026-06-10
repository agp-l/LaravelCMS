<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'published',
    ];
}

public function histories()
{
    return $this->hasMany(PageHistory::class, 'page_id')->orderBy('created_at', 'desc');
}