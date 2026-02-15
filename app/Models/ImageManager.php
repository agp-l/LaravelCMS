<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ImageManager extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['title', 'group', 'perex'];


    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('gallery')->useDisk('media_public');
        $this->addMediaCollection('pages')->useDisk('media_public');
    }

    public function registerMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(300)
            ->sharpen(10)
            ->nonQueued(); // provede se hned bez fronty
    }
}
