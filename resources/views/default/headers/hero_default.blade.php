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

<div id="myCarousel" class="carousel slide mb-0 bg-dark bg-gradient" @if($totalSlides > 1) data-bs-ride="carousel" @endif>
    
    {{-- Indikátory (tečky dole) --}}
    @if($totalSlides > 1)
        <div class="carousel-indicators mb-0 pb-2">
            <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
            @if($hasSlide2)<button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1"></button>@endif
            @if($hasSlide3)<button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2"></button>@endif
        </div>
    @endif

    <div class="carousel-inner pb-4 pb-lg-0">
        
        {{-- Slide 1: Text vlevo, Fotka vpravo --}}
        <div class="carousel-item active">
            <div class="container py-5 mt-lg-2 mb-lg-5 px-4">
                <div class="row align-items-center g-4 g-lg-5">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="display-5 fw-bold text-white mb-3 lh-sm text-uppercase text-shadow" style="letter-spacing: 1px;">{{ $title1 }}</h1>
                        {{-- Dekorativní modrá linka --}}
                        <div class="bg-info mx-auto mx-lg-0 mb-4" style="height: 2px; width: 80px; border-radius: 2px;"></div>
                        <p class="fs-6 text-white fw-normal mb-0 text-uppercase" style="letter-spacing: 2px;">{{ $text1 }}</p>
                    </div>
                    <div class="col-lg-6 text-center">
                        {{-- Rámeček pouze shora --}}
                        <img src="{{ $img1 }}" class="img-fluid rounded-4 shadow-lg border-start border-info border-2 p-2" alt="{{ $title1 }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 2: Fotka vlevo, Text vpravo (díky flex-lg-row-reverse) --}}
        @if($hasSlide2)
        <div class="carousel-item">
            <div class="container py-5 mt-lg-4 mb-lg-5 px-4">
                <div class="row align-items-center g-4 g-lg-5 flex-lg-row-reverse">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="display-5 fw-bold text-white mb-3 lh-sm text-uppercase text-shadow" style="letter-spacing: 1px;">{{ $title2 }}</h1>
                        {{-- Dekorativní modrá linka --}}
                        <div class="bg-info mx-auto mx-lg-0 mb-4" style="height: 2px; width: 80px; border-radius: 2px;"></div>
                        <p class="fs-6 text-white fw-normal mb-0 text-uppercase" style="letter-spacing: 2px;">{{ $text2 ?? '' }}</p>
                    </div>
                    <div class="col-lg-6 text-center">
                        {{-- Rámeček pouze shora --}}
                        <img src="{{ $img2 }}" class="img-fluid rounded-4 shadow-lg border-start border-info border-2 p-2" alt="{{ $title2 }}">
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 3: Text vlevo, Fotka vpravo --}}
        @if($hasSlide3)
        <div class="carousel-item">
            <div class="container py-5 mt-lg-4 mb-lg-5 px-4">
                <div class="row align-items-center g-4 g-lg-5">
                    <div class="col-lg-6 text-center text-lg-start">
                        <h1 class="display-5 fw-bold text-white mb-3 lh-sm text-uppercase text-shadow" style="letter-spacing: 1px;">{{ $title3 }}</h1>
                        {{-- Dekorativní modrá linka --}}
                        <div class="bg-info mx-auto mx-lg-0 mb-4" style="height: 2px; width: 80px; border-radius: 2px;"></div>
                        <p class="fs-6 text-white fw-normal mb-0 text-uppercase" style="letter-spacing: 2px;">{{ $text3 ?? '' }}</p>
                    </div>
                    <div class="col-lg-6 text-center">
                        {{-- Rámeček pouze shora --}}
                        <img src="{{ $img3 }}" class="img-fluid rounded-4 shadow-lg border-start border-info border-2 p-2" alt="{{ $title3 }}">
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Šipky pro posun --}}
    @if($totalSlides > 1)
        <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev" style="width: 5%;">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Předchozí</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next" style="width: 5%;">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Další</span>
        </button>
    @endif
</div>