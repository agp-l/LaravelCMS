@extends($layout ?? 'layouts.default.app')

@section('title', 'Články')

@section('content')
<main class="py-5 bg-light">
    <div class="container px-4" id="articles-list">
        
<div class="mb-5 text-center mx-auto">
    <h2 class="display-6 fw-bold text-dark mb-4">Články a zajímavosti</h2>
    
    @php
        // Zjistíme, jaká kategorie je zrovna v URL adrese aktivní
        $activeCategory = request()->route('category');
    @endphp

    <div class="d-flex flex-wrap justify-content-center gap-2">
        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.publicIndex', [], false)) }}"
           class="btn rounded-pill px-4 fw-medium shadow-sm {{ empty($activeCategory) ? 'btn-info text-white' : 'btn-outline-secondary text-black bg-white' }}">
            Všechny kategorie
        </a>
        
        @foreach ($categories as $cat)
            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.byCategory', ['category' => $cat], false)) }}"
               class="btn rounded-pill px-4 fw-medium shadow-sm {{ $activeCategory === $cat ? 'btn-info text-white' : 'btn-outline-secondary text-black bg-white' }}">
                {{ $cat }}
            </a>
        @endforeach
    </div>
</div>

        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3">
            @foreach ($articles as $article)
                @php
                    // Předpřipravený odkaz na článek, abychom ho nemuseli v HTML pořád opakovat
                    $articleUrl = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.show', ['slug' => $article->slug], false));
                @endphp

                <div class="col">
                    <div class="card h-100 border-0 bg-white rounded-4 shadow-sm overflow-hidden d-flex flex-column hover-card-up transition">
                        
                        @if ($article->image)
                            <a href="{{ $articleUrl }}" class="text-decoration-none">
                                <img src="{{ asset('img/blog/thumbs/' . $article->image) }}" 
                                     alt="{{ $article->title }}" 
                                     class="card-img-top w-100" 
                                     style="height: 220px; object-fit: cover;">
                            </a>
                        @endif

                        <div class="card-body p-4 d-flex flex-column">
                            <a href="{{ $articleUrl }}" class="text-decoration-none">
                                <h3 class="h5 fw-bold text-dark mb-3">{{ $article->title }}</h3>
                            </a>
                            
                            <p class="card-text text-muted lh-base mb-4 flex-grow-1">
                                {{ $article->perex }}
                            </p>
                            
                            <div class="mt-auto">
                                <a href="{{ $articleUrl }}" class="text-info fw-bold text-decoration-none d-inline-flex align-items-center">
                                    Číst článek <i class="fa-solid fa-arrow-right ms-2 fs-6"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5 pt-3">
            {{ $articles->withQueryString()->links() }}
        </div>
        
    </div>
</main>

<style>
    .hover-card-up {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-card-up:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection