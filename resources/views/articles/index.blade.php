@extends($layout ?? 'layouts.default.app')

@section('title', 'Články')

@section('content')
    <main>
        <div class="container px-4 py-2" id="featured-3">
            <a class="btn btn-secondary mx-1 mb-2"
                href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.publicIndex', [], false)) }}">
                Všechny kategorie
            </a>
            @foreach ($categories as $cat)
                <a class="btn btn-primary mx-1 mb-2"
                    href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.byCategory', ['category' => $cat], false)) }}">
                    {{ $cat }}
                </a>
            @endforeach

            <div class="row g-4 py-5 row-cols-1 row-cols-lg-3">
                @foreach ($articles as $article)
                    <div class="feature col">
                        @if ($article->image)
                            <div class="image-hover-wrapper mb-2">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $article->id }}">
                                    <img src="{{ asset('img/blog/thumbs/' . $article->image) }}" alt="{{ $article->title }}"
                                        class="img-fluid">
                                    <div class="overlay">
                                        <i class="bi bi-search"></i>
                                    </div>
                                </a>
                            </div>
                            <!-- Modal -->
                            <div class="modal fade" id="imageModal-{{ $article->id }}" tabindex="-1"
                                aria-labelledby="imageModalLabel-{{ $article->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content bg-transparent border-0">
                                        <div class="modal-body p-0 text-center">
                                            <img src="{{ asset('img/blog/full/' . $article->image) }}" alt="{{ $article->title }}"
                                                class="img-fluid rounded shadow">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        @endif

                        <h3 class="fs-5">{{ $article->title }}</h3>
                        <p class="fw-light lh-base" style="text-align: justify; text-justify: inter-character;">
                            {{ $article->perex }}
                        </p>
                        <a class="btn btn-outline-secondary"
                            href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.show', ['slug' => $article->slug], false)) }}"
                            role="button">
                            Zobrazit
                        </a>
                    </div>
                @endforeach
            </div>

            {{-- stránkování --}}
            {{ $articles->withQueryString()->links() }}
        </div>

    </main>
@endsection