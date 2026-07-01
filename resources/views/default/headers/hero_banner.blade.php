@php
    // Načtení dat ze shortcodu nebo nastavení výchozích hodnot
    $img = $img ?? '/img/slideohen.jpg';
    $subtitle = $subtitle ?? ''; // Drobný text nad nadpisem (např. "Kontakt")
    $title = $title ?? 'Vítejte na naší stránce';
    $text = $text ?? ''; // Popisek pod nadpisem
    
    // Tlačítka (nepovinná)
    $btn1_text = $btn1_text ?? '';
    $btn1_link = $btn1_link ?? '#';
    $btn2_text = $btn2_text ?? '';
    $btn2_link = $btn2_link ?? '#';

    // Síla ztmavení obrázku (0.1 je světlá, 0.8 je skoro černá). Výchozí 0.6.
    $overlay = $overlay ?? '0.6';
@endphp

<style>
    /* Styly speciálně pro tento banner */
    .custom-hero-bg {
        background-image: url('{{ $img }}');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }
    
    /* Pravidla pouze pro mobily a menší tablety */
    @media (max-width: 991.98px) {
        .custom-hero-bg {
            /* Vypne fixní pozadí na mobilu (řeší problém s gigantickým přiblížením na iOS) */
            background-attachment: scroll;
            
            /* Pokud opravdu chceš narvat celou fotku do prostoru bez ořezu (vzniknou ale pruhy), 
               vymaž lomítka a hvězdičky u následujícího řádku: */
            /* background-size: contain; background-repeat: no-repeat; background-color: #000; */
        }
    }
</style>

<div class="position-relative w-100 d-flex align-items-center justify-content-center overflow-hidden custom-hero-bg" 
     style="min-height: 50vh;">
    
    {{-- Tmavý filtr přes obrázek pro perfektní čitelnost textu --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, {{ $overlay }});"></div>

    {{-- Obsah banneru --}}
    <div class="container position-relative z-1 text-center py-5 text-white">
        
        @if($subtitle)
            <span class="d-block text-uppercase fw-bold mb-3" style="letter-spacing: 3px; color: #10b981; font-size: 0.85rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">
                {{ $subtitle }}
            </span>
        @endif

        <h1 class="display-4 fw-bolder mb-4" style="text-shadow: 2px 4px 8px rgba(0,0,0,0.7), 0px 0px 2px rgba(0,0,0,0.5);">
            {{ $title }}
        </h1>
        
        @if($text)
            <p class="lead mx-auto mb-5 fw-bold" style="max-width: 700px; text-shadow: 1px 2px 5px rgba(0,0,0,0.7), 0px 0px 2px rgba(0,0,0,0.5);">
                {{ $text }}
            </p>
        @endif

        {{-- Generování tlačítek --}}
        @if($btn1_text || $btn2_text)
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
                @if($btn1_text)
                    <a href="{{ $btn1_link }}" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow-lg" style="transition: all 0.3s ease;">
                        {{ $btn1_text }}
                    </a>
                @endif
                
                @if($btn2_text)
                    <a href="{{ $btn2_link }}" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold shadow-lg" style="transition: all 0.3s ease;">
                        {{ $btn2_text }}
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>