@extends($layout ?? 'layouts.default.app')

@section('title', 'Stránka nenalezena')

@section('content')
    <div class="container text-center my-5">
        <h1 class="display-4 text-danger">404</h1>
        <p class="lead">Omlouváme se, ale tato stránka neexistuje.</p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">
            <i class="fas fa-home"></i> Zpět na hlavní stránku
        </a>
    </div>
@endsection
