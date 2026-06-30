<section class="py-5 bg-light border-top border-bottom rounded-4 mx-2 mx-lg-4 my-4">
    <div class="container py-3">
        <div class="row align-items-center g-5">
            <div class="col-12 col-lg-6 text-start">
                <h2 class="display-6 fw-bold text-dark mb-4" style="line-height: 1.2;">
                    {{ $nadpis ?? 'Putujeme přírodou a učíme se novým dovednostem' }}
                </h2>
                <p class="lead lh-sm mb-4">
                    {!! $text1 ?? 'S dětmi zkoumám svět, bádáme, učíme se a hrajeme si, se staršími dobrodruhy společně plánujeme a uskutečňujeme velká dobrodružství.' !!}
                </p>
                <p class="lead lh-sm mb-4">
                    {!! $text2 ?? 'Na cestách najdou vaše děti nové přátele a zažijí spoustu zábavy a dobrodružství.' !!}
                </p>
                <a class="btn btn-info text-white fw-bold px-4 py-3 rounded-3 shadow-sm"
                    href="{{ $odkaz_url ?? 'https://dobrodruzi.cz/cs/stranka/vylety' }}">
                    <i class="{{ $odkaz_ikona ?? 'fa-solid fa-person-hiking' }} me-2"></i>{{ $odkaz_text ?? 'Výlety do přírody' }}
                </a>
            </div>
            <div class="col-12 col-lg-6">
                <div class="position-relative p-2 bg-white rounded-4 shadow-sm border border-info border-opacity-10">
                    <img class="img-fluid rounded-4 w-100 shadow-sm" src="{{ $obrazek_src ?? '/img/obr5.jpg' }}" alt="{{ $obrazek_alt ?? 'Děti putující přírodou' }}"
                        loading="lazy" style="object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
</section>