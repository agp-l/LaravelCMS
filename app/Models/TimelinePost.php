<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimelinePost extends Model
{
    // Vypneme automatické timestamps Laravelu, protože používáme jen tvé created_at
    public $timestamps = false;

    protected $fillable = [
        'created_at',
        'icon_class',
        'content',
        'map_url'
    ];

    // Ošetříme datum, aby s ním Laravel uměl pracovat jako s Carbon objektem
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Tento Accessor automaticky převede tvé [tagy] na platné HTML.
     * V Blade šabloně pak stačí zavolat $post->parsed_content
     */
    public function getParsedContentAttribute()
    {
        $text = $this->content;

        // Nahrazení [img]...[/img]
        $text = preg_replace('/\[img\](.*?)\[\/img\]/', '<img src="$1" alt="Obrázek" class="img-fluid rounded mt-3 mb-2 shadow-sm" style="max-width: 100%; height: auto;">', $text);
        
        // Nahrazení [url=...]...[/url]
        $text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/', '<a href="$1" target="_blank" class="text-primary fw-bold text-decoration-none">$2</a>', $text);
        
        // Nahrazení [b] a [p] a [br]
        $text = preg_replace('/\[b\](.*?)\[\/b\]/', '<strong>$1</strong>', $text);
        $text = preg_replace('/\[p\](.*?)\[\/p\]/', '<p class="mb-2">$1</p>', $text);
        $text = str_replace('[br]', '<br>', $text);

        // Obyčejné odřádkování převede na <br>
        return nl2br($text);
    }

    /**
     * Pomocná funkce pro střídání barev koleček (ikon) podle ID
     */
    public function getIconColorClassAttribute()
    {
        $colors = ['bg-info', 'bg-primary', 'bg-success', 'bg-warning', 'bg-danger'];
        return $colors[$this->id % count($colors)];
    }
}