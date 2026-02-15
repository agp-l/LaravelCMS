@extends($layout ?? 'layouts.default.app')
@section('title', 'Zapomenuté heslo')

@section('content')
<div class="container" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Zapomenuté heslo</h3>

    <p class="mb-3 text-muted text-center">
        Zadej svůj e-mail, na který ti zašleme odkaz pro obnovení hesla.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        {{-- E-mail --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mailová adresa</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-envelope"></i> Odeslat odkaz
            </button>
        </div>
    </form>

    <div class="mt-3 text-center">
        <a href="{{ route('login') }}">← Zpět na přihlášení</a>
    </div>
</div>
@endsection
