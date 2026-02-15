@extends($layout ?? 'layouts.default.app')

@section('title', 'Nová výjimka pro layout')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Přidat výjimku</h2>
    <form method="POST" action="{{ route('admin.layout-overrides.store') }}">
        @csrf
        <div class="mb-3">
            <label for="path_pattern" class="form-label">Vzor URL (např. cs/clanek/*)</label>
            <input type="text" class="form-control" name="path_pattern" id="path_pattern" required>
        </div>

        <div class="mb-3">
            <label for="layout" class="form-label">Název layoutu (např. layouts.mizzle.app)</label>
            <input type="text" class="form-control" name="layout" id="layout" required>
        </div>

        <button type="submit" class="btn btn-success">Uložit výjimku</button>
    </form>
</div>
@endsection
