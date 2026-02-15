@extends('layouts.app')

@section('title', 'Upravit odkaz')

@section('content')
    <div class="container">
        <h1 class="mb-4">Upravit odkaz v menu</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('menu.update', $menu->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="label" class="form-label">Název odkazu</label>
                <input type="text" name="label" id="label" class="form-control" value="{{ old('label', $menu->label) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">Cílová URL</label>
                <input type="text" name="url" id="url" class="form-control" value="{{ old('url', $menu->url) }}" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Typ odkazu</label>
                <select name="type" id="type" class="form-select">
                    <option value="page" {{ old('type', $menu->type) === 'page' ? 'selected' : '' }}>Statická stránka</option>
                    <option value="article" {{ old('type', $menu->type) === 'article' ? 'selected' : '' }}>Článek</option>
                    <option value="external" {{ old('type', $menu->type) === 'external' ? 'selected' : '' }}>Externí odkaz
                    </option>
                </select>
            </div>

            <div class="mb-3">
                <label for="parent_id" class="form-label">Nadřazená položka</label>
                <select name="parent_id" id="parent_id" class="form-select">
                    <option value="">— žádná —</option>
                    @foreach ($menus as $m)
                        <option value="{{ $m->id }}" @if(old('parent_id', $menu->parent_id) == $m->id) selected @endif>
                            {!! str_repeat('— ', $m->level) !!}{{ $m->label }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="mb-3">
                <label for="order" class="form-label">Pořadí</label>
                <input type="number" name="order" id="order" class="form-control" value="{{ old('order', $menu->order) }}">
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="published" id="published" value="1" {{ old('published', $menu->published) ? 'checked' : '' }}>
                <label class="form-check-label" for="published">
                    Zveřejnit
                </label>
            </div>

            <button type="submit" class="btn btn-primary">💾 Uložit změny</button>
            <a href="{{ route('menu.index') }}" class="btn btn-secondary">Zpět</a>
        </form>
    </div>

@endsection