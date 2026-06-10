<!DOCTYPE html>
<html lang="cs">
<head>
    @include('skolka.head')
</head>
<body>

    @include('skolka.navbar')
    @include('skolka.carousel')

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('skolka.footer')
    @include('skolka.scripts')




    
</body>
</html>












