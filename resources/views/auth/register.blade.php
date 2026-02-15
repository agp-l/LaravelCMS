@extends($layout ?? 'layouts.default.app')

@section('title', 'Registrace')

@section('content')
<div class="container" style="max-width: 500px;">
    <h3 class="mb-4 text-center">Registrace</h3>

    @if ($errors->any())
    <div class="alert alert-danger">
        <strong>Došlo k chybám při odeslání formuláře:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif



    <form method="POST" action="{{ route('admin.register') }}">
        @csrf

        {{-- Jméno --}}
        <div class="mb-3">
            <label for="name" class="form-label">Jméno</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- E-mail --}}
        <div class="mb-3">
            <label for="email" class="form-label">E-mailová adresa</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email') }}" required>
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

        {{-- Potvrzení hesla --}}
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Potvrzení hesla</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>

        {{-- Odeslat --}}
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-user-plus"></i> Zaregistrovat se
            </button>
        </div>
    </form>

    <div class="mt-3 text-center">
        <a href="{{ route('login') }}">← Už máš účet? Přihlas se</a>
    </div>
</div>
@endsection
