<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light p-4 rounded-4 text-center shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mx-auto mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona1 ?? 'fa-solid fa-person-hiking' }} fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $nadpis1 ?? 'Výlety​​​' }}</h5>
                    <p class="fw-light mb-3">
                        {!! $text1 ?? 'Cestovatelské a tábornické znalosti, dovednosti přežití. Zkušený průvodce, respektující přístup a spousta zábavy.​' !!}
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light p-4 rounded-4 text-center shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mx-auto mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona2 ?? 'fa-solid fa-users' }} fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $nadpis2 ?? 'Kroužky​' }}</h5>
                    <p class="fw-light mb-3">
                        {!! $text2 ?? 'Většinu času trávíme v pohybu, v přírodě, za každého počasí. Věnujeme se, pohybovým hrám a vzdělávacím činnostem.​' !!}
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light p-4 rounded-4 text-center shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mx-auto mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona3 ?? 'fa-solid fa-campground' }} fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $nadpis3 ?? 'Kurzy' }}</h5>
                    <p class="fw-light mb-3">
                        {!! $text3 ?? 'Nabízím vzdělávací činnosti, výuku programování, sjíždění řek na nafukovacích kánojích, cestovatelské dovednosti a znalosti. ​' !!}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>