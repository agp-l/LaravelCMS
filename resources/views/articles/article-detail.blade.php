@extends($layout ?? 'layouts.default.app')

@section('title', $article->title)

@section('content')
    <main class="py-5 bg-white">

        <div class="container-fluid px-4 px-xl-5" style="max-width: 1500px;">

            <div class="row">

                <div class="col-12 col-lg-6 offset-lg-3">
        <h2 class="display-7 fw-bold text-dark text-uppercase mb-4 lh-sm">{{ $article->title }}</h2>
                    <div class="mb-4">
                        <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.publicIndex', [], false)) }}"
                            class="text-info text-decoration-none fw-bold d-inline-flex align-items-center transition hover-link">
                            <i class="fa-solid fa-arrow-left me-2"></i> Zpět na přehled článků
                        </a>
                    </div>

                    {{-- Administrátorská tlačítka --}}
                    @auth
                        <div
                            class="mb-4 p-3 bg-light rounded-4 d-flex flex-wrap gap-2 align-items-center border border-warning border-opacity-25 shadow-sm">
                            <span class="text-muted small fw-bold me-auto text-uppercase"><i class="fa-solid fa-lock me-1"></i>
                                Admin panel</span>
                            <a href="{{ route('article.edit', $article->id) }}"
                                class="btn btn-sm btn-warning rounded-pill px-3 fw-medium">Upravit</a>
                            <form action="{{ route('article.toggle', $article->id) }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm {{ $article->published ? 'btn-outline-secondary' : 'btn-success' }} rounded-pill px-3 fw-medium shadow-sm">
                                    {{ $article->published ? 'Skrýt článek' : 'Zveřejnit článek' }}
                                </button>
                            </form>
                        </div>
                    @endauth

            

                    <div class="article-content text-dark fw-light">
                        {!! $article->content !!}
                    </div>

                </div>

                <div class="col-12 col-lg-3 mt-5 mt-lg-0">

                    <div class="card border-0 bg-light rounded-4 p-4 shadow-sm mb-4">
                        <h4 class="h5 fw-bold text-dark mb-4">Další čtení</h4>

                        <form method="GET" action="" class="mb-4">
                            <select name="category" class="form-select border-0 shadow-sm rounded-3 py-2"
                                onchange="this.form.submit()">
                                <option value="">Všechny kategorie</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </form>

                        <div class="list-group list-group-flush border-0">
                            @foreach ($sideArticles->take(5) as $item)
                                <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.show', ['slug' => $item->slug, 'category' => request('category')], false)) }}"
                                    class="list-group-item list-group-item-action bg-transparent px-0 py-2 border-secondary border-opacity-10 {{ $article->id === $item->id ? 'text-info fw-bold' : 'text-dark' }}">
                                    <i
                                        class="fa-solid fa-chevron-right text-info me-2 small opacity-50"></i>{{ $item->title }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-info bg-opacity-10 border-start border-info border-4 p-4 rounded-4 shadow-sm">
                        <h5 class="fw-bold text-info mb-3 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                            Propagace a změna</h5>
                        <p class="text-dark fw-medium lh-base mb-0 small">
                            Mým cílem je šíření povědomí o sebeřízeném vzdělávání, propaguji cesty jak obcházet diktát
                            většiny, nabízím prostředky a příležitosti ke svobodnému vzdělávání.
                        </p>
                    </div>

                </div>
            </div>

        </div>
    </main>

    <style>
        .article-content {
            line-height: 1.6 !important;
            font-size: 1.1rem;
        }

        .article-content p {
            margin-bottom: 1.1rem !important;
        }

        .article-content img {
            max-width: 100%;
            height: auto;
            border-radius: 0.5rem;
            margin-bottom: 1.1rem;
        }

        .hover-link {
            transition: transform 0.2s ease-in-out, opacity 0.2s ease-in-out;
        }

        .hover-link:hover {
            opacity: 0.8;
            transform: translateX(-5px);
        }
    </style>
@endsection
