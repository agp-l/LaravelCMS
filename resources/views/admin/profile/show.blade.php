@extends($layout ?? 'layouts.default.app')


@section('title', 'Profil uživatele')

@section('content')
<div class="container my-5 mt-5">
        <h3 class="mb-4">Profil</h3>

        <div class="card p-4">
            @if(is_object($user))
            <p><strong>Jméno:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
        @else
            <p class="text-danger">Uživatel není dostupný.</p>
        @endif

            <a href="{{ route('profile.edit') }}" class="btn btn-primary mt-3">Upravit profil</a>
            <a href="{{ route('profile.delete') }}" class="btn btn-outline-danger mt-4">Smazat účet</a>

        </div>
    </div>
@endsection
