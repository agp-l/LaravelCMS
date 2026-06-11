@auth
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                
                    @if (session('tinymce_disabled'))
                        <a href="{{ route('toggle.tinymce') }}" class="nav-link"><i class="fad fa-toggle-off"></i> Zapnout editor</a>
                    @else
                        <a href="{{ route('toggle.tinymce') }}" class="nav-link"><i class="fad fa-toggle-on"></i> Vypnout editor</a>
                    @endif
                    <li class="nav-item"><a class="nav-link" href="{{ route('article.index') }}"><i class="fad fa-newspaper"></i>Články</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('page.index') }}"><i class="fad fa-file-alt"></i> Stránky</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('menu.index') }}"><i class="fad fa-bars"></i> Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('article.create') }}"><i class="fad fa-plus-circle"></i> Nový článek</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('page.create') }}"><i class="fad fa-plus-square"></i> Nová stránka</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profile.show') }}"><i class="fad fa-user-cog"></i> Profil</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('images.index') }}"><i class="fa-regular fa-image"></i> Obrázky</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.register') }}"><i class="fad fa-user-plus"></i> Nový uživatel</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('admin.layout-overrides.index') }}"><i class="fad fa-user-plus"></i>Téma</a></li>
                   
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="nav-link btn btn-link" type="submit"><i class="fad fa-sign-out-alt"></i> Odhlásit</button>
                        </form>
                    </li>
              <!--  <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i class="fad fa-sign-in-alt"></i> Přihlásit</a></li>-->
                    
            
            </ul>
        </div>
    </div>
</nav>
@endauth