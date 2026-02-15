<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Article extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'published',
        'category',
        'perex',
        'image', // ten můžeš později vynechat, pokud přejdeš čistě na MediaLibrary
    ];
}


