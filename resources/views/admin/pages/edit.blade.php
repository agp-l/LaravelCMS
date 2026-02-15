@extends('layouts.app')

@section('content')
<div class="container">
    <h1>✏️ Upravit stránku</h1>

    <form action="{{ route('page.update', $page->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Název stránky</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $page->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug (adresa)</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Obsah</label>
            <textarea name="content" id="content" class="form-control" rows="6">{{ old('content', $page->content) }}</textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="published" id="published" {{ $page->published ? 'checked' : '' }}>
            <label class="form-check-label" for="published">Zveřejnit</label>
        </div>

        <button type="submit" class="btn btn-success">💾 Uložit změny</button>
    </form>
</div>
@endsection
