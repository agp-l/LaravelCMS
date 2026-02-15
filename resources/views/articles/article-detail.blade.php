<!-- resources/views/article-detail.blade.php -->
@extends($layout ?? 'layouts.default.app')

@section('title', $article->title)

@section('content')
    <div class="container">
        <section class="article my-5">
            <div class="row">
                <div class="col-md-2"></div>




                <div class="col-md-6 px-4" style="text-align: justify; text-justify: inter-character;">


                    {{-- Tlačítka pro přihlášeného uživatele --}}
                    @auth
                        <div class="mb-3 text-end">
                            <a href="{{ route('article.edit', $article->id) }}" class="btn btn-sm btn-warning">Upravit</a>

                            <form action="{{ route('article.toggle', $article->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-secondary">
                                    {{ $article->published ? 'Skrýt' : 'Zveřejnit' }}
                                </button>
                            </form>
                        </div>
                    @endauth



                    <h2>{{ $article->title }}</h2>


                    <div class="lead mb-4">{!! $article->content !!}</div>
                </div>

                <div class="col-md-1"></div>

                <div class="col-md-3">
                    <div id="list" class="list-group mb-4">

                        <form method="GET" action="" class="mb-3">
                            <select name="category" class="form-select form-select" onchange="this.form.submit()">
                                <option value="">Všechny kategorie</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                        </form>



                        <div class="list-group-item list-group-item-primary">Seznam článků</div>

                        @foreach ($sideArticles as $item)
                        <a class="list-group-item list-group-item-action"
                           href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.show', ['slug' => $item->slug, 'category' => request('category')], false)) }}">
                            {{ $item->title }}
                        </a>
                    @endforeach
                    </div>

                    <div class="article px-2">
                        <p class="mt-5">PROPAGACE A ZMĚNA</p>
                        <span class="fw-light lh-base">
                            <p>Mým cílem je šíření povědomí o sebeřízeném vzdělávání, propaguji cesty jak obcházet diktát
                                většiny, nabízím prostředky a příležitosti ke svobodnému vzdělávání.</p>
                        </span>


                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection