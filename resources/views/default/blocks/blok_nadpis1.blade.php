<section class="py-5 bg-white">
    <div class="container py-4 text-center">
        <div class="text-info small text-uppercase tracking-wider fw-bold mb-2">
            <hr class="d-inline-block border-info border-1 opacity-50 mb-1 me-2" style="width: 24px;">
            {{ $podnadpis ?? 'Dobrodruzi.cz' }}
            <hr class="d-inline-block border-info border-1 opacity-50 mb-1 ms-2" style="width: 24px;">
        </div>
        <h1 class="display-4 fw-bold text-dark mb-4">
            {{ $nadpis ?? 'Sebeřízené vzdělávání' }}
        </h1>
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <p class="lead text-muted mb-0">
                    {!! $text ?? 'Vytvářím pro děti z Jindřichovic pod Smrkem <strong>příležitosti</strong> k všestrannému rozvoji, pořádám výlety, expedice, kurzy, kroužky, věnujeme se vzdělávání, praktickým dovednostem, a dalším volnočasovým aktivitám a hrám.' !!}
                </p>
            </div>
        </div>
    </div>
</section>