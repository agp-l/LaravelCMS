@extends($layout ?? 'layouts.default.app')

@section('title', 'Obnovení hesla')

@section('content')
<div class="container" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Obnovení hesla</h3>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mailová adresa</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Nové heslo --}}
        <div class="mb-3">
            <label for="password" class="form-label">Nové heslo</label>
            <input type="password" name="password" id="password" class="form-control" required>
            @error('password')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- Potvrzení hesla --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Potvrzení hesla</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-unlock-alt"></i> Obnovit heslo
            </button>
        </div>
    </form>
</div>
@endsection
