<style>
    /* Čára se zobrazí jen na menších displejích (pod 992px) */
    @media (max-width: 991.98px) {
        .mobile-separator {
            border-bottom: 1px solid #f8f9fa; /* barva border-light */
        }
    }
</style>

<nav class="navbar navbar-expand-lg bg-white mt-4 mb-2">
    <div class="container">
        
        <a href="/" class="d-flex align-items-center mb-0 me-md-auto text-dark text-decoration-none">
            <img src="{{ asset('img/logo.jpg') }}" alt="Logo dobrodruzi" height="40" class="me-2 rounded">
            <span class="fs-4 fw-normal text-dark">dobrodruzi.cz</span>
        </a>

        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
            aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse mt-3 mt-lg-0" id="mainNavbar">
            <ul class="navbar-nav ms-auto text-center text-lg-start">
                @foreach ($menuTree as $item)
                    @if (count($item->children))
                        <li class="nav-item dropdown mobile-separator">
                            <a class="nav-link dropdown-toggle py-3 py-lg-2 fw-normal" href="{{ getMenuUrl($item) }}"
                                id="menu{{ $item->id }}" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ $item->label }}
                            </a>
                            <ul class="dropdown-menu border-0 shadow-sm text-center text-lg-start" aria-labelledby="menu{{ $item->id }}">
                                @foreach ($item->children as $child)
                                    <li><a class="dropdown-item py-2" href="{{ getMenuUrl($child) }}">{{ $child->label }}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    @else
                        <li class="nav-item mobile-separator">
                            <a class="nav-link py-3 py-lg-2 fw-normal" href="{{ getMenuUrl($item) }}">{{ $item->label }}</a>
                        </li>
                    @endif
                @endforeach
            </ul>

            <div class="d-flex justify-content-center justify-content-lg-start my-3 my-lg-0 ms-lg-3">
                @include('default.language-switch')
            </div>

            @auth
                <ul class="navbar-nav text-center text-lg-start ms-lg-2 mt-2 mt-lg-0">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle btn btn-outline-info border-0 py-2" href="#" id="admin" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa-regular fad fa-bars me-1"></i> Admin
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow text-center text-lg-start" aria-labelledby="admin">
                            {{-- Položky administrace zůstávají beze změn --}}
                            @if (session('tinymce_disabled'))
                                <li><a href="{{ route('toggle.tinymce') }}" class="dropdown-item py-2"><i class="fad fa-toggle-off me-2 text-muted"></i> Zapnout editor</a></li>
                            @else
                                <li><a href="{{ route('toggle.tinymce') }}" class="dropdown-item py-2"><i class="fad fa-toggle-on me-2 text-success"></i> Vypnout editor</a></li>
                            @endif
                            
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item py-2" href="{{ route('article.index') }}"><i class="fad fa-newspaper me-2 text-primary"></i> Články</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('page.index') }}"><i class="fad fa-file-alt me-2 text-primary"></i> Stránky</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('menu.index') }}"><i class="fad fa-bars me-2 text-primary"></i> Menu</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('diary.admin') }}"><i class="fad fa-book-open me-2 text-primary"></i> Cestovní deník</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li><a class="dropdown-item py-2" href="{{ route('article.create') }}"><i class="fad fa-plus-circle me-2 text-success"></i> Nový článek</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('page.create') }}"><i class="fad fa-plus-square me-2 text-success"></i> Nová stránka</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li><a class="dropdown-item py-2" href="{{ route('admin.reservations.index') }}"><i class="fad fa-calendar-alt me-2 text-warning"></i> Seznam rezervací</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('admin.activities.index') }}"><i class="fad fa-sliders-h me-2 text-warning"></i> Nastavení aktivit</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('admin.blocks.index') }}"><i class="fad fa-calendar-times me-2 text-warning"></i> Dispečink a výluky</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('admin.revenue.index') }}"><i class="fa-solid fa-wallet me-2 text-warning"></i> Finanční přehled</a></li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li><a class="dropdown-item py-2" href="{{ route('profile.show') }}"><i class="fad fa-user-cog me-2 text-info"></i> Profil</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('images.index') }}"><i class="fa-regular fa-image me-2 text-info"></i> Obrázky</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('admin.register') }}"><i class="fad fa-user-plus me-2 text-info"></i> Nový uživatel</a></li>
                            <li><a class="dropdown-item py-2" href="{{ route('admin.layout-overrides.index') }}"><i class="fad fa-palette me-2 text-info"></i> Téma</a></li>

                            <li><hr class="dropdown-divider"></li>

                            <li class="dropdown-item p-0">
                                <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                    @csrf
                                    <button class="nav-link btn btn-link w-100 text-start text-lg-start text-danger py-2 px-3 m-0 rounded-0" type="submit">
                                        <i class="fad fa-sign-out-alt me-2"></i> Odhlásit
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            @endauth
        </div>
    </div>
</nav>