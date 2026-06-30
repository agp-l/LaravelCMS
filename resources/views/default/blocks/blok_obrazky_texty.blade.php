<section class="py-5 bg-light border-top border-bottom mx-2 mx-lg-4 my-4">
    <div class="container py-3">

        <div class="text-center mb-5 mx-auto" style="max-width: 800px;">
            <div class="text-info small text-uppercase tracking-wider fw-bold mb-2">
                <hr class="d-inline-block border-info border-1 opacity-50 mb-1 me-2" style="width: 24px;">
                {{ $podnadpis ?? 'Bezpečný rozvoj' }}
                <hr class="d-inline-block border-info border-1 opacity-50 mb-1 ms-2" style="width: 24px;">
            </div>
            <h2 class="display-6 fw-bold text-dark mb-4">{{ $nadpis ?? 'Učíme se prostřednictvím přírody' }}</h2>
            <p class="lead text-muted mb-5">
                {!! $text_uvod ?? 'Volnočasové aktivity organizuji převážně v přírodě, protože pobyt venku a pohyb na čerstvém vzduchu podporuje zdraví a odolnost dětí. Děti poznávají okolí místa, kde žijí, osvojují si praktické cestovatelské dovednosti a učí se rozpoznat přirozená nebezpečí.' !!}
            </p>
        </div>

        <div class="row g-4 justify-content-center text-center text-md-start">
            
            <div class="col-md-4">
                <div class="card border-0 bg-transparent h-100">
                    <img src="{{ $obr1_src ?? '/img/sm-b1.jpg' }}" alt="{{ $obr1_alt ?? 'Děti v kolektivu' }}" class="img-fluid rounded-4 shadow-sm mb-4"
                        style="object-fit: cover; aspect-ratio: 4/3;">
                    <p class="fw-light mb-0 px-2">
                        {!! $text1 ?? 'Děti se učí žít v kolektivu, konstruktivně řešit sociální situace a respektovat domluvená pravidla.' !!}
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 bg-transparent h-100">
                    <img src="{{ $obr2_src ?? '/img/sm-b4.jpg' }}" alt="{{ $obr2_alt ?? 'Kreativita dětí' }}" class="img-fluid rounded-4 shadow-sm mb-4"
                        style="object-fit: cover; aspect-ratio: 4/3;">
                    <p class="fw-light mb-0 px-2">
                        {!! $text2 ?? 'Vytvářejí si pozitivní vztah sami k sobě, a neustále na sobě pracují. Podporujeme jejich kreativitu a zručnost.' !!}
                    </p>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card border-0 bg-transparent h-100">
                    <img src="{{ $obr3_src ?? '/img/sm-b2.jpg' }}" alt="{{ $obr3_alt ?? 'Rituály a přátelství' }}" class="img-fluid rounded-4 shadow-sm mb-4"
                        style="object-fit: cover; aspect-ratio: 4/3;">
                    <p class="fw-light mb-0 px-2">
                        {!! $text3 ?? 'Poznávají hodnotu přátelství a sdílení, prožívají společně rituály. Osvojují si správné řešení konfliktů a efektivní komunikaci.' !!}
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>