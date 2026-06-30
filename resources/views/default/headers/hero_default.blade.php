@php
    // Nastavení výchozích hodnot pro 1. slide, pokud by se shortcode zavolal prázdný
    $img1 = $img1 ?? '/img/slideohen.jpg';
    $title1 = $title1 ?? 'Putujeme přírodou a učíme se novým dovednostem';
    $text1 = $text1 ?? 'Provázím mladé dobrodruhy na cestách za poznáním';

    // Detekce, zda jsme v shortcodu vyplnili i data pro 2. a 3. slide
    $hasSlide2 = !empty($img2) && !empty($title2);
    $hasSlide3 = !empty($img3) && !empty($title3);
    
    // Spočítáme, kolik slidů celkem máme (pro zapnutí/vypnutí šipek a indikátorů)
    $totalSlides = 1 + ($hasSlide2 ? 1 : 0) + ($hasSlide3 ? 1 : 0);
@endphp

<div id="myCarousel" class="carousel slide mb-0" @if($totalSlides > 1) data-bs-ride="carousel" @endif>
    
    {{-- Indikátory (tečky dole) se ukážou jen, když je víc než 1 slide --}}
    @if($totalSlides > 1)
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"></button>
            @if($hasSlide2)<button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1"></button>@endif
            @if($hasSlide3)<button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2"></button>@endif
        </div>
    @endif

    <div class="carousel-inner">
        
        {{-- Slide 1 (Zobrazuje se vždy) --}}
        <div class="carousel-item active"
            style="background-image: url('{{ $img1 }}'); background-size: cover; background-position: center; height: 500px;">
            <div class="overlay"></div>
            <div class="container h-100 d-flex align-items-center justify-content-center">
                <div class="text-center text-white">
                    <h1 class="display-6 fw-bold my_size text-uppercase text-shadow">{{ $title1 }}</h1>
                    <h5 class="text-uppercase text-shadow">{{ $text1 }}</h5>
                </div>
            </div>
        </div>

        {{-- Slide 2 (Zobrazí se, jen když existuje $img2 a $title2) --}}
        @if($hasSlide2)
        <div class="carousel-item"
            style="background-image: url('{{ $img2 }}'); background-size: cover; background-position: center; height: 500px;">
            <div class="overlay"></div>
            <div class="container h-100 d-flex align-items-center justify-content-center">
                <div class="text-center text-white">
                    <h1 class="display-6 fw-bold my_size text-uppercase text-shadow">{{ $title2 }}</h1>
                    <h5 class="text-uppercase text-shadow">{{ $text2 ?? '' }}</h5>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 3 (Zobrazí se, jen když existuje $img3 a $title3) --}}
        @if($hasSlide3)
        <div class="carousel-item"
            style="background-image: url('{{ $img3 }}'); background-size: cover; background-position: center; height: 500px;">
            <div class="overlay"></div>
            <div class="container h-100 d-flex align-items-center justify-content-center">
                <div class="text-center text-white">
                    <h1 class="display-6 fw-bold my_size text-uppercase text-shadow">{{ $title3 }}</h1>
                    <h5 class="text-uppercase text-shadow">{{ $text3 ?? '' }}</h5>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Šipky pro posun se ukážou jen, když je víc než 1 slide --}}
    @if($totalSlides > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Předchozí</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Další</span>
        </button>
    @endif
</div>