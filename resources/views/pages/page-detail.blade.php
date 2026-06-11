


@extends($layout ?? 'layouts.default.app')
@section('header')
    @if(isset($headerData) && $headerData)
        {{-- Pokud uživatel zadal hlavičku, načteme šablonu podle parametru "typ" (výchozí je 'hero') --}}
        @include('partials.headers.' . ($headerData['typ'] ?? 'hero'), $headerData)
    @else
        {{-- Pokud uživatel nezadal nic, načteme výchozí velký carousel --}}
        @include('partials.carousel')
    @endif
@endsection

@section('title', $page->title)

@section('content')

    <section class="page my-0">

            {{-- Tlačítka pro přihlášeného uživatele --}}
            @auth
                <div class="mb-0 text-end">
                    <a href="{{ route('page.edit', $page->id) }}" class="btn btn-sm btn-warning">Upravit</a>

                    <form action="{{ route('page.toggle', $page->id) }}" m  ethod="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary">
                            {{ $page->published ? 'Skrýt' : 'Zveřejnit' }}
                        </button>
                    </form>
                </div>
            @endauth



        <!--<h1 class="display-6 fw-bold text-center mb-2">{{ $page->title }}</h1>-->


          
                @php
                    $blocks = json_decode($page->content, true);
                @endphp
            
                @if (is_array($blocks))
                    @foreach ($blocks as $block)
                        @includeIf('components.pageblocks.' . $block['type'], ['columns' => $block['columns'] ?? []])
                    @endforeach
                @else
                <div>{!! $page->content !!}</div>
                @endif
    
    </section>

@endsection

