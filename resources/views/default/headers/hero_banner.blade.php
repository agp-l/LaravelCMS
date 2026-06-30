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

<div class="position-relative w-100 d-flex align-items-center justify-content-center overflow-hidden" 
     style="min-height: 50vh; background-image: url('{{ $img }}'); background-size: cover; background-position: center; background-attachment: fixed;">
    
    {{-- Tmavý filtr přes obrázek pro perfektní čitelnost textu --}}
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0, 0, 0, {{ $overlay }});"></div>

    {{-- Obsah banneru --}}
    <div class="container position-relative z-1 text-center py-5 text-white">
        
        @if($subtitle)
            <span class="d-block text-uppercase fw-bold mb-3" style="letter-spacing: 3px; color: #10b981; font-size: 0.85rem;">
                {{ $subtitle }}
            </span>
        @endif

        <h1 class="display-4 fw-bolder mb-4 text-shadow" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">
            {{ $title }}
        </h1>
        
        @if($text)
            <p class="lead mx-auto mb-5 text-shadow fw-bold " style="max-width: 700px; text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                {{ $text }}
            </p>
        @endif

        {{-- Generování tlačítek vedle sebe (nebo pod sebou na malém mobilu) --}}
        @if($btn1_text || $btn2_text)
            <div class="d-flex justify-content-center gap-3 flex-wrap mt-2">
                @if($btn1_text)
                    <a href="{{ $btn1_link }}" class="btn btn-success btn-lg rounded-pill px-5 fw-bold shadow">
                        {{ $btn1_text }}
                    </a>
                @endif
                
                @if($btn2_text)
                    <a href="{{ $btn2_link }}" class="btn btn-outline-light btn-lg rounded-pill px-5 fw-bold shadow">
                        {{ $btn2_text }}
                    </a>
                @endif
            </div>
        @endif

    </div>
</div>