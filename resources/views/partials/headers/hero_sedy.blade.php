<section class="py-5 text-white rounded-4 mx-2 mx-lg-4 my-2"
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
                <p class="lead mb-5" style="font-size: 1.15rem;">
                    {{ $text }}
                </p>
            @endif
            
        
        </div>
    </div>
</section>