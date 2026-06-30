<section class="py-5 bg-light border-top border-bottom mx-2 mx-lg-4 my-4">
    <div class="container py-4">
        <div class="row align-items-center g-5 flex-lg-row-reverse">
            <div class="col-12 col-lg-6 text-center">
                <div class="p-2 bg-light rounded-4 shadow-sm border border-secondary border-opacity-10">
                    <img src="{{ $obrazek_src ?? '/img/knihy.png' }}" class="img-fluid rounded-4 w-100" alt="{{ $obrazek_alt ?? 'Respektující literatura' }}"
                        loading="lazy">
                </div>
            </div>
            <div class="col-12 col-lg-6 text-start">
                <div class="text-info small text-uppercase tracking-wider fw-bold mb-2">
                    {{ $podnadpis ?? 'Knihy, ze kterých vycházím' }}
                </div>
                <h2 class="display-8 fw-bold text-dark mb-4" style="line-height: 1.2;">
                    {{ $nadpis ?? 'Respekt, svoboda, zodpovědnost​' }}
                </h2>
                <p class="lead fs-5 mb-5">
                    {!! $text ?? 'Klíčem ke spokojenému životu jsou dobré mezilidské vztahy, ty představují zdroj většiny radostí i bolestí v lidském životě. Mladí dobrodruzi pěstují vztahy založené na vzájemném respektu a úctě.​' !!}
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ $tlacitko1_url ?? 'https://www.peoplecomm.cz/respektovat-a-byt-respektovan' }}"
                        class="btn btn-outline-info text-black fw-medium rounded-pill px-4">{{ $tlacitko1_text ?? 'Respektovat a být res.' }}</a>
                    <a href="{{ $tlacitko2_url ?? 'https://www.peoplecomm.cz/kniha-summerhill' }}"
                        class="btn btn-outline-info text-black fw-medium rounded-pill px-4">{{ $tlacitko2_text ?? 'Demokratická škola' }}</a>
                    <a href="{{ $tlacitko3_url ?? 'https://www.peoplecomm.cz/svoboda-uceni' }}"
                        class="btn btn-outline-info text-black fw-medium rounded-pill px-4">{{ $tlacitko3_text ?? '​​Svoboda učení' }}</a>
                </div>
            </div>
        </div>
    </div>
</section>