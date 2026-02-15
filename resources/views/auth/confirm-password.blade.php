@extends($layout ?? 'layouts.default.app')

@section('title', 'Potvrzení hesla')

@section('content')
<div class="container py-5" style="max-width: 500px">
    <h2 class="mb-4">Potvrzení hesla</h2>

    <div class="alert alert-info small">
        Tato část aplikace je chráněná. Pro pokračování prosím potvrďte své heslo.
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="mb-3">
            <label for="password" class="form-label">Heslo</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required autocomplete="current-password">

            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                Potvrdit
            </button>
        </div>
    </form>
</div>
@endsection
