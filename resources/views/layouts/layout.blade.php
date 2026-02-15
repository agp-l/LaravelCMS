@php
    $menuItems = \App\Models\Menu::whereNull('parent_id')->where('published', true)->orderBy('order')->get();
@endphp

@foreach ($menuItems as $item)
    @php
        $children = $item->children()->where('published', true)->orderBy('order')->get();
        $hasDropdown = $children->isNotEmpty();
        $url = '#';
        if ($item->type === 'page') {
            $page = \App\Models\Page::find($item->related_id);
            $url = $page ? route('page.show', $page->slug) : '#';
        } elseif ($item->type === 'article') {
            $article = \App\Models\Article::find($item->related_id);
            $url = $article ? route('article.show', $article->slug) : '#';
        } elseif ($item->type === 'custom') {
            $url = $item->url;
        }
    @endphp

    @if ($hasDropdown)
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="{{ $url }}" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                {{ $item->title }}
            </a>
            <ul class="dropdown-menu">
                @foreach ($children as $child)
                    @php
                        $childUrl = $child->type === 'page' ? route('page.show', \App\Models\Page::find($child->related_id)?->slug ?? '') :
                                    ($child->type === 'article' ? route('article.show', \App\Models\Article::find($child->related_id)?->slug ?? '') :
                                    $child->url);
                    @endphp
                    <li><a class="dropdown-item" href="{{ $childUrl }}">{{ $child->title }}</a></li>
                @endforeach
            </ul>
        </li>
    @else
        <li class="nav-item">
            <a class="nav-link" href="{{ $url }}">{{ $item->title }}</a>
        </li>
    @endif
@endforeach
