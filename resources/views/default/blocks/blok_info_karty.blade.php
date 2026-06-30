<section class="py-5 bg-white">
    <div class="container py-3">
        <div class="row g-4 justify-content-center">

            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light p-4 rounded-4 text-center shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mx-auto mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona1 ?? 'fa-solid fa-map-location-dot' }} fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $nadpis1 ?? 'Místa konání' }}</h5>
                    <p class="fw-light mb-0">
                        {!! $text1 ??
                            'Scházíme se v Jindřichovicích pod Smrkem, nejlepší učebna je sama příroda, v létě i v zimě využíváme zázemí <a href="https://goo.gl/maps/itTNU4573LkMN6Jj7" target="_blank" class="text-info fw-semibold text-decoration-none">základny</a> Svobodům.' !!}
                    </p>
                    <a href="{{ $odkaz1_url ?? 'https://goo.gl/maps/itTNU4573LkMN6Jj7' }}"
                        class="btn btn-sm btn-outline-info text-black rounded-pill fw-medium px-3 mt-auto">{{ $odkaz1_text ?? 'Zobrazit mapu' }}</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light p-4 rounded-4 text-center shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mx-auto mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona2 ?? 'fa-solid fa-calendar-days' }} fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $nadpis2 ?? 'Termíny akcí' }}</h5>
                    <p class="fw-light mb-3">
                        {{ $text2 ?? 'Na výlety vyrážíme o víkendech, kurzy a kroužky se konají ve všední dny.' }}
                    </p>
                    <a href="{{ $odkaz2_url ?? 'https://dobrodruzi.cz/rezervace/' }}"
                        class="btn btn-sm btn-outline-info text-black rounded-pill fw-medium px-3 mt-auto">{{ $odkaz2_text ?? 'Zobrazit kalendář' }}</a>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 border-0 bg-light p-4 rounded-4 text-center shadow-sm">
                    <div class="d-flex align-items-center justify-content-center bg-white text-info rounded-circle mx-auto mb-4 shadow-sm"
                        style="width: 60px; height: 60px;">
                        <i class="{{ $ikona3 ?? 'fa-solid fa-hand-holding-heart' }} fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-3">{{ $nadpis3 ?? 'Ceny' }}</h5>
                    <p class="fw-light mb-2">
                        {{ $text3 ?? 'Dopravu, jídlo a ubytování na výletech si hradí děti sami (výdaje v řádu stokorun). Více informací najdete na stránce cen.' }}
                    </p>
                    <a href="{{ $odkaz3_url ?? 'https://dobrodruzi.cz/cs/stranka/cenik' }}"
                        class="btn btn-sm btn-outline-info text-black rounded-pill fw-medium px-3 mt-auto">{{ $odkaz3_text ?? 'Přejít na Ceny' }}</a>
                </div>
            </div>

        </div>
    </div>
</section>
