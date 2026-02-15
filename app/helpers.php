<?php

use App\Models\Page;
use App\Models\Article;

function getMenuUrl($menu)
{
    switch ($menu->type) {
        case 'page':
            return route('page.show', ['slug' => $menu->url]);
        case 'article':
            return route('article.show', ['slug' => $menu->url]);
        case 'external':
        default:
            return $menu->url;
    }
}

function buildMenuTree($items, $parentId = null)
{
    $branch = [];

    foreach ($items as $item) {
        if ($item->parent_id == $parentId) {
            $children = buildMenuTree($items, $item->id);
            $item->children = $children;
            $branch[] = $item;
        }
    }

    return $branch;
}
