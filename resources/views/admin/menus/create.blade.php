@extends('layouts.app')

@section('title', 'Vytvořit novou položku menu')

@section('content')
    <div class="container">

        <h1 class="mb-4">➕ Nová položka menu</h1>

        <form action="{{ route('menu.store') }}" method="POST">
            @csrf

            <!-- Název -->
            <div class="mb-3">
                <label for="label" class="form-label">Text odkazu</label>
                <input type="text" name="label" id="label" class="form-control" required>
            </div>

            <!-- URL -->
            <div class="mb-3">
                <label for="url" class="form-label">URL nebo slug</label>
                <input type="text" name="url" id="url" class="form-control" required>
            </div>

            <!-- Typ odkazu -->
            <div class="mb-3">
                <label for="type" class="form-label">Typ odkazu</label>
                <select name="type" id="type" class="form-select" required>
                    <option value="page" {{ old('type') === 'page' ? 'selected' : '' }}>Statická stránka</option>
                    <option value="article" {{ old('type') === 'article' ? 'selected' : '' }}>Článek</option>
                    <option value="external" {{ old('type') === 'external' ? 'selected' : '' }}>Externí odkaz</option>
                </select>
            </div>


            <!-- Nadřazená položka -->
            <div class="mb-3">
                <label for="parent_id" class="form-label">Podřazené menu (volitelné)</label>
                <select name="parent_id" id="parent_id" class="form-select">
                    <option value="">Žádné – hlavní položka</option>
                    @foreach ($menus as $menu)
                        <option value="{{ $menu->id }}">{{ $menu->label }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Pořadí -->
            <div class="mb-3">
                <label for="order" class="form-label">Pořadí</label>
                <input type="number" name="order" id="order" class="form-control" value="0">
            </div>

            <!-- Zveřejnit -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="published" id="published" value="1" checked>
                <label class="form-check-label" for="published">Zveřejnit</label>
            </div>

            <button type="submit" class="btn btn-primary">💾 Uložit</button>
        </form>
    </div>
@endsection