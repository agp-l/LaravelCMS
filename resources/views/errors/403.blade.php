@extends($layout ?? 'layouts.default.app')

@section('title', 'Přístup odepřen')

@section('content')
    <div class="container text-center mt-5">
        <h1 class="display-4 text-warning">403</h1>
        <p class="lead">Nemáš oprávnění k zobrazení této stránky.</p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
            <i class="fas fa-home"></i> Zpět na hlavní stránku
        </a>
    </div>
@endsection
