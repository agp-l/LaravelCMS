<!DOCTYPE html>
<html lang="cs">
<head>
    @include('partials.head')
</head>
<body>

    @include('partials.navbar')
    
    {{-- Zde se dynamicky vloží hlavička podle toho, co řekne konkrétní stránka --}}
    @yield('header')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.scripts')




    
</body>
</html>












