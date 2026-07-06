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

    $slides = [
        ['title' => $title1, 'text' => $text1, 'img' => $img1],
    ];
    if ($hasSlide2) {
        $slides[] = ['title' => $title2, 'text' => $text2 ?? '', 'img' => $img2];
    }
    if ($hasSlide3) {
        $slides[] = ['title' => $title3, 'text' => $text3 ?? '', 'img' => $img3];
    }
@endphp

<section class="hero-default-header mt-0 mx-2 mx-lg-4 rounded-4 shadow-lg text-white border-top border-info border-4 overflow-hidden position-relative"
    style="background: linear-gradient(135deg, #03444c 0%, #07d1db 100%);">

    <div id="myCarousel" class="carousel slide" @if($totalSlides > 1) data-bs-ride="carousel" @endif>

        @if($totalSlides > 1)
            <div class="carousel-indicators mb-0">
                @foreach($slides as $index => $slide)
                    <button type="button"
                        data-bs-target="#myCarousel"
                        data-bs-slide-to="{{ $index }}"
                        class="{{ $index === 0 ? 'active' : '' }}"
                        @if($index === 0) aria-current="true" @endif
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
        @endif

        <div class="carousel-inner pb-0">
            @foreach($slides as $index => $slide)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="container py-3 py-lg-4 position-relative">
                        <div class="row align-items-center g-3 g-lg-4">
                            <div class="col-lg-7 text-center text-lg-start">
                                <h1 class="display-5 fw-bold mb-2 text-white" style="line-height: 1.2; letter-spacing: -0.5px;">
                                    {{ $slide['title'] }}
                                </h1>

                                <p class="lead mb-0 mx-auto mx-lg-0 text-white-90" style="max-width: 560px; font-size: 1.05rem;">
                                    {{ $slide['text'] }}
                                </p>
                            </div>

                            <div class="col-lg-5 text-center">
                                <div class="hero-default-img-frame bg-white text-dark rounded-3 shadow border border-info border-opacity-10 mx-auto" style="max-width: 380px;">
                                    <div class="hero-default-img-wrap">
                                        <img src="{{ $slide['img'] }}"
                                            alt="{{ $slide['title'] }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

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
</section>
