<section class="mt-0 mx-2 mx-lg-4 rounded-4 shadow-lg text-white border-top border-info border-4"
    style="background: linear-gradient(135deg, #03444c 0%, #07d1db 100%);">
    <div class="container py-5 position-relative">
        <div class="row align-items-center g-5 py-3">
            <div class="col-lg-7 text-center text-lg-start">
                
                @if(!empty($badge))
                <div class="badge bg-info text-dark px-3 py-2 rounded-pill mb-4 fw-bold shadow-sm">
                    <i class="fa-solid fa-sparkles me-2"></i>{{ $badge }}
                </div>
                @endif

                <h1 class="display-3 fw-bold mb-4 text-white" style="line-height: 1.15; letter-spacing: -1px;">
                    {{ $title1 ?? 'Hlavní nadpis' }}<br>
                    <span class="text-info">{{ $title2 ?? '' }}</span>
                </h1>
                
                <p class="lead mb-5 mx-auto mx-lg-0 text-white-90" style="max-width: 560px; font-size: 1.2rem;">
                    {{ $text ?? 'Zde je nějaký doplňující text.' }}
                </p>
            </div>

            <div class="col-lg-5 text-center">
                <div class="bg-white text-dark p-3 rounded-4 shadow-lg border border-info border-opacity-10">
                    <img src="{{ $img ?? 'https://rezervace.dobrodruzi.cz/images/klucina.jpg' }}" alt="Obrázek v hlavičce"
                        class="img-fluid rounded-3 shadow-sm">
                </div>
            </div>
        </div>
    </div>
</section>