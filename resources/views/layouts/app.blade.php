<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="utf-8">
    <title>@yield('title', 'CMS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="https://dobrodruzi.cz/admin/favicon.ico">
<link rel="stylesheet" href="{{ asset('font/css/fontawesome.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/solid.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/regular.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/brands.css') }}">

    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .carousel-item {
            position: relative;
            height: 500px;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            overflow: hidden;
        }

        .carousel-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            height: 20px;
            width: 100%;
            background-image:
                linear-gradient(-135deg, white 10px, transparent 0),
                linear-gradient(135deg, white 10px, transparent 0);
            background-position: left top;
            background-repeat: repeat-x;
            background-size: 20px 20px;
            z-index: 2;
        }


        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4);
            /* průhledné ztmavení */
            z-index: 1;
        }

        .carousel-item .container {
            position: relative;
            z-index: 2;
        }


        .text-shadow {
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9);
        }


        .overlay {
            background: linear-gradient(to bottom, rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.6));
        }





        /* Obecný styl pro hlavní navigační odkazy */
        .navbar-nav .nav-link {
            color: rgb(44, 119, 231);
            /* Bootstrap modrá */
        }


        /* Hover změna barvy textu */
        .navbar-nav .nav-link:hover {
            color: rgb(0, 0, 0);
            /* tmavší modrá */
        }


        .bg-dark .navbar-nav .nav-link {
            color: #ffffff;
        }

        .bg-dark .navbar-nav .nav-link:hover {
            color: #cccccc;
        }
    </style>

</head>

<body>






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
                                <a class="nav-link dropdown-toggle" href="{{ getMenuUrl($item) }}" id="menu{{ $item->id }}"
                                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ $item->label }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="menu{{ $item->id }}">
                                    @foreach ($item->children as $child)
                                        <li><a class="dropdown-item" href="{{ getMenuUrl($child) }}">{{ $child->label }}</a></li>
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
            </div>
        </div>
    </nav>







    <div id="myCarousel" class="carousel slide mb-0" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active"
                style="background-image: url('/img/slideohen.jpg'); background-size: cover; background-position: center; height: 500px;">
                <div class="overlay"></div>
                <div class="container h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center text-white">
                        <h1 class="display-6 fw-bold my_size text-uppercase text-shadow">Putujeme přírodou a učíme se
                            novým dovednostem</h1>
                        <h5 class="text-uppercase text-shadow">Provázím mladé dobrodruhy na cestách za poznáním</h5>
                    </div>
                </div>
            </div>
            <div class="carousel-item"
                style="background-image: url('/img/slidelod.jpg'); background-size: cover; background-position: center; height: 500px;">
                <div class="overlay"></div>
                <div class="container h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center text-white">
                        <h1 class="display-6 fw-bold my_size text-uppercase text-shadow">Nabízím zážitky na celý život
                        </h1>
                        <h5 class="text-uppercase text-shadow">výlety I expedice I vzdělávací kurzy</h5>
                    </div>
                </div>
            </div>
            <div class="carousel-item"
                style="background-image: url('/img/slidealex.jpg'); background-size: cover; background-position: center; height: 500px;">
                <div class="overlay"></div>
                <div class="container h-100 d-flex align-items-center justify-content-center">
                    <div class="text-center text-white">
                        <h1 class="display-6 fw-bold my_size text-uppercase text-shadow">Vytvářím pro děti příležitosti
                            k seberozvoji</h1>
                        <h5 class="text-uppercase text-shadow">A trávíme čas ve zdravém prostředí</h5>
                    </div>
                </div>
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Předchozí</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Další</span>
        </button>
    </div>



    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">

                    @auth
                        @if (session('tinymce_disabled'))
                            <a href="{{ route('toggle.tinymce') }}" class="nav-link"><i class="fad fa-toggle-off"></i> Zapnout
                                editor</a>
                        @else
                            <a href="{{ route('toggle.tinymce') }}" class="nav-link"><i class="fad fa-toggle-on"></i> Vypnout
                                editor</a>
                        @endif
                        <li class="nav-item"><a class="nav-link" href="{{ route('article.index') }}"><i
                                    class="fad fa-newspaper"></i> Blog</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('page.index') }}"><i
                                    class="fad fa-file-alt"></i> Stránky</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('menu.index') }}"><i
                                    class="fad fa-bars"></i> Menu</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('article.create') }}"><i
                                    class="fad fa-plus-circle"></i> Nový článek</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('page.create') }}"><i
                                    class="fad fa-plus-square"></i> Nová stránka</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('profile.edit') }}"><i
                                    class="fad fa-user-cog"></i> Profil</a></li>
                        <li class="nav-item">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="nav-link btn btn-link" type="submit"><i class="fad fa-sign-out-alt"></i>
                                    Odhlásit</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><i
                                    class="fad fa-sign-in-alt"></i> Přihlásit</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}"><i
                                    class="fad fa-user-plus"></i> Registrovat</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>





    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @yield('content')










    <footer class="bg-dark text-secondary pt-5"
        style="background-image: linear-gradient(rgba(33, 37, 41, 0.6),rgba(33, 37, 41, 0.9)), url('https://dobrodruzi.cz/img/slide/brno.png'); background-position: left 50%; background-size: cover; background-repeat: no-repeat;">
        <div class="container">

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4 pb-4">

                <div class="col">
                    <h5 class="text-light">NABÍZÍME</h5>
                    <ul class="list-unstyled">
                        <li class="lh-base fw-light">VÝLETY</li>
                        <li class="lh-base fw-light">EXPEDICE</li>
                        <li class="lh-base fw-light">KURZY</li>
                        <li class="lh-base fw-light">KROUŽKY</li>
                    </ul>
                </div>

                <div class="col">
                    <h5 class="text-light">SYMBOLIZUJE NÁS</h5>
                    <ul class="list-unstyled">
                        <li class="lh-base fw-light">DOBRODRUŽSTVÍ</li>
                        <li class="lh-base fw-light">PŘÍRODA</li>
                        <li class="lh-base fw-light">KAMARÁDI</li>
                        <li class="lh-base fw-light">CHARAKTER</li>
                    </ul>
                </div>

                <div class="col">
                    <h5 class="text-light">NAŠE HODNOTY</h5>
                    <ul class="list-unstyled">
                        <li class="lh-base fw-light">SVOBODA</li>
                        <li class="lh-base fw-light">RESPEKT</li>
                        <li class="lh-base fw-light">ZODPOVĚDNOST</li>
                        <li class="lh-base fw-light">SPOLUPRÁCE</li>
                    </ul>
                </div>

                <div class="col">
                    <h5 class="text-light">PRINCIPY</h5>
                    <ul class="list-unstyled">
                        <li class="lh-base fw-light">ÚČEL NESVĚTÍ PROSTŘEDKY</li>
                        <li class="lh-base fw-light">OBOUSTRANNÁ DOHODA</li>
                        <li class="lh-base fw-light">NENÁSILNÁ KOMUNIKACE</li>
                        <li class="lh-base fw-light">DOBROVOLNOST A VOLBA</li>
                    </ul>
                </div>

            </div>

            <hr class="border-secondary mt-0">

            <div class="text-center py-3 small fw-light">
                Copyright © dobrodruzi.cz | paralelní svobodné společenství | Licence na text: Creative Commons CC BY-SA
                4.0
            </div>
        </div>
    </footer>















    <!-- TinyMCE (z CDN) -->
    <script src="https://cdn.tiny.cloud/1/dcpt068f6z0iexoyf3nng4ck3m92hgfr53phm4opmcqv405v/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .normalize('NFD')                  // Odstraní diakritiku
                .replace(/[\u0300-\u036f]/g, '')   // Další diakritika
                .replace(/[^a-z0-9\s-]/g, '')      // Odstraní speciální znaky
                .trim()
                .replace(/\s+/g, '-')              // Mezera → pomlčka
                .replace(/-+/g, '-');              // Více pomlček → jedna
        }

        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function () {
                    if (!slugInput.value || slugInput.value === slugify(slugInput.value)) {
                        slugInput.value = slugify(titleInput.value);
                    }
                });
            }
        });
    </script>


    @if (!session('tinymce_disabled'))
        <script>
            tinymce.init({
                selector: 'textarea#content',
                plugins: 'lists link image code',
                toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
                menubar: false,
                height: 400,
                entity_encoding: 'raw',

                // 🔽 Tohle úplně vypne validaci a přepisování:
                verify_html: false,
                valid_elements: '*[*]',
                extended_valid_elements: '*[*]',
                valid_children: '+body[*]',
                forced_root_block: false, // pokud chceš povolit i fragmenty bez <p>

                // Volitelně:
                // content_css: false, // neaplikuje žádné výchozí styly

                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,


            });
        </script>
    @endif

    <!-- Bootstrap Bundle (včetně Popper.js) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>