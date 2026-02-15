@extends('layouts.app')

@section('title', 'Upravit stránku')

@section('content')
    <div class="container">

        <h1>Upravit stránku</h1>

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

        <form method="POST" action="{{ route('article.update', $article->id) }}">
            @csrf
            @method('PUT')


            <div class="mb-3">
                <label for="slug" class="form-label">URL slug</label>
                <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $article->slug) }}">
                <div class="form-text">Použije se v adrese: /stranka/<strong><em>slug</em></strong></div>
            </div>


            <div class="mb-3">
                <label for="title" class="form-label">Název stránky</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $article->title) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label">Obsah</label>
                <textarea name="content" id="content" rows="6"
                    class="form-control">{{ old('content', $article->content) }}</textarea>

            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="published" id="published" class="form-check-input" {{ old('published', $article->published) ? 'checked' : '' }}>
                <label for="published" class="form-check-label">Zveřejnit</label>
            </div>

            <button type="submit" class="btn btn-primary"> Uložit změny</button>
            <a href="{{ route('article.index') }}" class="btn btn-secondary">Zpět</a>
        </form>
    </div>
@endsection