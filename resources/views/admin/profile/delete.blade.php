@extends($layout ?? 'layouts.default.app')


@section('title', 'Smazat účet')

@section('content')
    <div class="container my-5 mt-5">
        <h3 class="mb-4 text-danger">Smazat účet</h3>

        <p class="mb-3">Tímto trvale smažeš svůj účet včetně všech údajů. Tento krok nelze vrátit zpět.</p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')

            <div class="mb-3">
                <label for="password" class="form-label">Potvrď heslo</label>
                <input type="password" name="password" id="password" class="form-control" required>
                @error('userDeletion.password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-danger">Trvale smazat účet</button>
            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Zpět</a>
        </form>
    </div>
@endsection