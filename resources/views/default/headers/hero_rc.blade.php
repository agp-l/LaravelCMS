<section class="py-5 border-top mt-3">
    <div class="container py-4">
        <div class="row align-items-center g-5">

            <div class="col-lg-6 text-center text-lg-start">
                
                {{-- Odznak (Badge) --}}
                @if(!empty($badge))
                    <div class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill mb-3 fw-bold border border-info border-opacity-25">
                        <i class="{{ $badge_icon ?? 'fa-solid fa-flag-checkered' }} me-2"></i>{{ $badge }}
                    </div>
                @endif

                {{-- Hlavní nadpis (rozdělený na tmavou a světle modrou část) --}}
                @if(!empty($title1) || !empty($title2))
                    <h1 class="display-4 fw-bold mb-3 text-dark" style="line-height: 1.15; letter-spacing: -1px;">
                        {{ $title1 }} 
                        @if(!empty($title2))
                            <br><span class="text-info">{{ $title2 }}</span>
                        @endif
                    </h1>
                @endif

                {{-- Textový odstavec s podporou HTML tagů --}}
                @if(!empty($text))
                    <p class="lead mb-4 text-muted" style="font-size: 1.1rem; max-width: 500px; margin: 0 auto 0 0;">
                        {!! $text !!}
                    </p>
                @endif
                
            </div>

            {{-- Obrázek --}}
            @if(!empty($img))
                <div class="col-lg-6">
                    <div class="position-relative p-2 bg-white rounded-4 shadow-lg border border-info border-opacity-10 overflow-hidden" style="height: 350px;">
                        <img src="{{ $img }}" alt="{{ $img_alt ?? 'Obrázek k sekci' }}" class="w-100 h-100 rounded-3 shadow-sm" style="object-fit: cover;">
                    </div>
                </div>
            @endif

        </div>
    </div>
</section>