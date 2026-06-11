@extends($layout ?? 'layouts.default.app')

@section('title', 'Správa vzhledu webu')

@section('content')
<div class="container mt-4 mb-5">
    <h2><i class="fa-solid fa-palette me-2"></i>Nastavení vzhledu webu</h2>

    <div class="card mt-4 border-0 shadow-sm rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.layout-overrides.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label for="layout" class="form-label fw-bold">Zadejte název šablony</label>
                    <input type="text" name="layout" id="layout" class="form-control form-control-lg" value="{{ $currentTheme ?? 'layouts.default.app' }}" required>
                    <div class="form-text mt-2">
                        Příklady: <code>layouts.default.app</code> nebo <code>layouts.mizzle.app</code>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                    <i class="fa-solid fa-save me-2"></i>Uložit vzhled celého webu
                </button>
            </form>
        </div>
    </div>
</div>
@endsection