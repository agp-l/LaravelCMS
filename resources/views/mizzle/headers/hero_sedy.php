<section class="py-5 text-white rounded-4 mx-2 mx-lg-4 my-5"
    style="background: linear-gradient(135deg, #74808c 0%, #383e44 100%); text-align: center;">
    <div class="container py-4">
        <div class="mx-auto" style="max-width: 750px;">
            
            {{-- Ikonka (pokud není zadaná, nevykreslí se) --}}
            @if(!empty($icon))
                <i class="{{ $icon }} fs-1 text-info mb-3"></i>
            @endif
            
            {{-- Hlavní nadpis --}}
            @if(!empty($title))
                <h2 class="display-5 fw-bold text-white mb-4">{{ $title }}</h2>
            @endif
            
            {{-- Podnadpis / Text --}}
            @if(!empty($text))
                <p class="lead text-white-50 mb-5" style="font-size: 1.15rem;">
                    {{ $text }}
                </p>
            @endif
            
            {{-- Tlačítka --}}
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                
                {{-- Primární (modré) tlačítko --}}
                @if(!empty($btn1_text))
                    <a href="{{ $btn1_url ?? '#' }}"
                        class="btn btn-info text-dark fw-bold btn-lg px-4 py-3 rounded-3 shadow-sm">
                        {{ $btn1_text }}
                    </a>
                @endif
                
                {{-- Sekundární (obrysové) tlačítko --}}
                @if(!empty($btn2_text))
                    <a href="{{ $btn2_url ?? '#' }}"
                        class="btn btn-outline-light btn-lg px-4 py-3 rounded-3">
                        {{ $btn2_text }}
                    </a>
                @endif

            </div>
        </div>
    </div>
</section>