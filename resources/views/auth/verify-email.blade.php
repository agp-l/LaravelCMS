@extends($layout ?? 'layouts.default.app')

@section('title', 'Ověření e-mailu')

@section('content')
    <div class="container py-5" style="max-width: 600px">
        <h2 class="mb-4">Ověřte svůj e-mail</h2>

        @if (session('status') === 'verification-link-sent')
            <div class="alert alert-success">
                Na vaši e-mailovou adresu byl odeslán nový ověřovací odkaz.
            </div>
        @endif

        <p class="mb-4">
            Před pokračováním prosím ověřte svůj e-mail kliknutím na odkaz, který jsme vám poslali.
            Pokud jste e-mail neobdrželi, můžeme vám ho znovu odeslat.
        </p>

        <form method="POST" action="{{ route('verification.send') }}" class="d-inline-block me-2">
            @csrf
            <button type="submit" class="btn btn-primary">
                Znovu odeslat ověřovací e-mail
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="d-inline-block">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">
                Odhlásit se
            </button>
        </form>
    </div>
@endsection