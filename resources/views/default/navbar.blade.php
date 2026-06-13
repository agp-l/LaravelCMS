<!--<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom mb-4"></nav>-->
<nav class="navbar navbar-expand-lg bg-white mt-4 mb-2">
    <div class="container">
        <!-- Logo vlevo -->
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo dobrodruzi" height="40" class="me-2">
            <span class="fs-4">dobrodruzi.cz</span>
        </a>
        <!-- Toggle pro mobil -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Menu -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto">
                @foreach ($menuTree as $item)
                    @if (count($item->children))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="{{ getMenuUrl($item) }}"
                                id="menu{{ $item->id }}" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ $item->label }}
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="menu{{ $item->id }}">
                                @foreach ($item->children as $child)
                                    <li><a class="dropdown-item" href="{{ getMenuUrl($child) }}">{{ $child->label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ getMenuUrl($item) }}">{{ $item->label }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>

            @include('default.language-switch')

            @auth
                <!-- Admin -->
                <span class="nav-item dropdown dropdown-animation">
                    <button class="btn btn-link mb-0 px-2" id="admin" data-bs-toggle="dropdown">
                        <i class="fa-regular fad fa-bars"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="admin">

                        @if (session('tinymce_disabled'))
                            <li><a href="{{ route('toggle.tinymce') }}" class="dropdown-item"><i
                                        class="fad fa-toggle-off"></i>
                                    Zapnout editor</a></li>
                        @else
                            <li><a href="{{ route('toggle.tinymce') }}" class="dropdown-item"><i
                                        class="fad fa-toggle-on"></i>
                                    Vypnout editor</a></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('article.index') }}"><i class="fad fa-newspaper"></i>
                                Články</a></li>
                        <li><a class="dropdown-item" href="{{ route('page.index') }}"><i class="fad fa-file-alt"></i>
                                Stránky</a></li>
                        <li><a class="dropdown-item" href="{{ route('menu.index') }}"><i class="fad fa-bars"></i> Menu</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ route('diary.admin') }}"><i class="fad fa-book-open"></i>
                                Cestovní deník</a></li>
                        <li><a class="dropdown-item" href="{{ route('article.create') }}"><i
                                    class="fad fa-plus-circle"></i> Nový článek</a></li>
                        <li><a class="dropdown-item" href="{{ route('page.create') }}"><i class="fad fa-plus-square"></i>
                                Nová stránka</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.reservations.index') }}"><i
                                    class="fad fa-calendar-alt"></i> Seznam rezervací</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.activities.index') }}"><i
                                    class="fad fa-sliders-h"></i> Nastavení aktivit</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.blocks.index') }}"><i class="fad fa-calendar-times"></i> Dispečink a výluky</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fad fa-user-cog"></i>
                                Profil</a></li>
                        <li><a class="dropdown-item" href="{{ route('images.index') }}"><i class="fa-regular fa-image"></i>
                                Obrázky</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.register') }}"><i class="fad fa-user-plus"></i>
                                Nový uživatel</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.layout-overrides.index') }}"><i
                                    class="fad fa-palette"></i> Téma</a></li>
                                    


                        <li class="dropdown-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="nav-link btn btn-link" type="submit"><i class="fad fa-sign-out-alt"></i>
                                    Odhlásit</button>
                            </form>
                        </li>
                        <!--  <li><a class="dropdown-item" href="{{ route('login') }}"><i class="fad fa-sign-in-alt"></i> Přihlásit</a></li>-->


                    </ul>
                </span>
            @endauth
        </div>
    </div>
</nav>
