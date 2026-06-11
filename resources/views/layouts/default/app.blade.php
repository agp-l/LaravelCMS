<!DOCTYPE html>
<html lang="cs">
<head>
    @include('default.head')
</head>
<body>

  @include('default.navbar')

    @hasSection('header')
        @yield('header')
    @else
        @include('default.carousel')
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

    @include('default.footer')
    @include('default.scripts')




    
</body>
</html>












