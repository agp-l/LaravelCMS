@extends('layouts.app')

@section('content')
<div class="container">
    
    <h1>➕ Vytvořit novou stránku</h1>

    <form action="{{ route('page.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Název stránky</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug (adresa)</label>
            <input type="text" name="slug" id="slug" class="form-control">
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Obsah</label>
            <textarea name="content" id="content" class="form-control" rows="6"></textarea>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="published" id="published">
            <label class="form-check-label" for="published">Zveřejnit</label>
        </div>

        <button type="submit" class="btn btn-primary">💾 Uložit stránku</button>
    </form>
</div>
@endsection
