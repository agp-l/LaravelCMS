<!DOCTYPE html>
<html lang="cs">
<head>
    @include('mizzle.head')
</head>
<body>



   @include('mizzle.navbar')

    {{-- Pokud stránka pošle svou hlavičku, vypíšeme ji. Jinak dáme defaultní carousel. --}}
    @hasSection('header')
        @yield('header')
    @else
        @include('mizzle.carousel')
    @endif


    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('mizzle.footer')
    @include('mizzle.scripts')

</body>
</html>












