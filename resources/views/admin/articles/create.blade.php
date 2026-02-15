@extends($layout ?? 'layouts.default.app')

@section('title', 'Přidat novou stránku')

@section('content')
    <div class="container my-5">

        <h1><i class="fad fa-plus-circle"></i> Přidat nový článek</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Chyba!</strong> Něco je špatně.
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" enctype="multipart/form-data" action="{{ route('article.store') }} ">
            @csrf


            <div class="mb-3">
                <label for="title" class="form-label">Název stránky</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
            </div>


            <div class="mb-3">
                <label for="slug" class="form-label">URL slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug') }}">
                <div class="form-text">Použije se v adrese: /stranka/<strong><em>slug</em></strong></div>
            </div>


            <div class="mb-3">
                <label for="category" class="form-label">Kategorie</label>
                <input type="text" name="category" id="category" class="form-control"
                    value="{{ old('category', $article->category ?? '') }}">
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Obrázek</label>
                <input class="form-control" type="file" name="image" id="image" accept="image/*">

                @if (!empty($article->image))
                    <small class="text-muted">Aktuální: {{ $article->image }}</small>
                    <div class="mt-2">
                        <img src="{{ asset('img/blog/' . $article->image) }}" alt="Náhled" class="img-fluid rounded"
                            style="max-height: 150px;">
                    </div>
                @endif
            </div>


            <div class="mb-3">
                <label for="perex" class="form-label">Krátký popis (perex)</label>
                <textarea name="perex" id="perex" class="form-control"
                    rows="3">{{ old('perex', $article->perex ?? '') }}</textarea>
            </div>


            <div class="mb-3">
                <label for="content" class="form-label">Obsah</label>
                <textarea name="content" id="content" rows="6" class="form-control">{{ old('content') }}</textarea>
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="published" id="published" class="form-check-input" {{ old('published') ? 'checked' : '' }}>
                <label for="published" class="form-check-label">Zveřejnit</label>
            </div>

            <button type="submit" class="btn btn-primary">Uložit stránku</button>
            <a href="{{ route('article.index') }}" class="btn btn-secondary">Zpět</a>
        </form>
    </div>
@endsection