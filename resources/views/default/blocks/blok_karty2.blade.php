<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-4 justify-content-center">

            <div class="col-md-6">
                <div class="card h-100 border-0 bg-light p-4 p-md-5 rounded-4 shadow-sm text-start">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona1 ?? 'fa-solid fa-arrows-alt' }} fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">{{ $nadpis1 ?? 'Připravuji řízený program formou nabídky' }}</h4>
                    <p class="fw-light mb-0">
                        {!! $text1 ?? 'Využívám <strong>vnitřní motivace</strong> dětí, aby se mohly věnovat věcem proto, že v nich vidí <strong>smysl</strong>, že je baví a že samy chtějí.' !!}
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-0 bg-light p-4 p-md-5 rounded-4 shadow-sm text-start">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona2 ?? 'fa-solid fa-handshake-angle' }} fs-4"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-3">{{ $nadpis2 ?? '„Jsem pro děti parťák, nikoli diktátor“' }}</h4>
                    <p class="fw-light mb-0">
                        {!! $text2 ?? 'Děti vedu k samostatnosti, přistupuji k nim individuálně a s <strong>respektem</strong>. Zajišťuji bezpečné a motivující prostředí, ve kterém se mohou všestranně rozvíjet. Dávám jim prostor pro volnou hru a svobodné učení.' !!}
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>