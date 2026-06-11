@extends($layout ?? 'layouts.default.app')

@section('header')
    @php
        // Bezpečná detekce složky šablony na základě aktivního layoutu
        $themeFolder = str_contains($layout ?? 'default', 'mizzle') ? 'mizzle' : 'default';
    @endphp
    
    @if(isset($headerData) && $headerData)
        {{-- Načtení konkrétní upravené hlavičky --}}
        @include($themeFolder . '.headers.' . ($headerData['typ'] ?? 'hero'), $headerData)
    @else
        {{-- Automatický fallback na výchozí carousel dané šablony --}}
        @include($themeFolder . '.carousel')
    @endif
@endsection

@section('title', $page->title)

@section('content')
    <section class="page my-0">

        @auth
            <div class="mb-0 text-end">
                <a href="{{ route('page.edit', $page->id) }}" class="btn btn-sm btn-warning">Upravit</a>

                <form action="{{ route('page.toggle', $page->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-secondary">
                        {{ $page->published ? 'Skrýt' : 'Zveřejnit' }}
                    </button>
                </form>
            </div>
        @endauth
        
        <div>{!! $page->content !!}</div>
    
    </section>
@endsection