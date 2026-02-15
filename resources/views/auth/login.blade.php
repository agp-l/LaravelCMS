@extends($layout ?? 'layouts.default.app')

@section('title', 'Přihlášení')

@section('content')
<div class="container py-5" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Přihlášení do administrace</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mailová adresa</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Heslo --}}
        <div class="mb-3">
            <label for="password" class="form-label">Heslo</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Zapamatovat si mě --}}
        <div class="mb-3 form-check">
            <input type="checkbox" name="remember" id="remember" class="form-check-input"
                   {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Zapamatovat si mě</label>
        </div>

        {{-- Odeslat --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-sign-in-alt"></i> Přihlásit se
            </button>
        </div>

        {{-- Zapomenuté heslo --}}
        @if (Route::has('password.request'))
            <div class="mt-3 text-center">
                <a href="{{ route('password.request') }}">Zapomenuté heslo?</a>
            </div>
        @endif
    </form>
</div>
@endsection
