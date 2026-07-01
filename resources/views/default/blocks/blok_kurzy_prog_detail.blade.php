@php
    // Načtení dat ze shortcodu nebo nastavení výchozích hodnot
    $img = $img ?? '/img/slidelod.jpg';
    $badge = $badge ?? 'Naše filozofie';
    $title = $title ?? 'Svoboda a zodpovědnost v rovnováze';
    $text =
        $text ??
        'Každý den přináší nové výzvy. Děti u nás mají prostor samy objevovat svět, ať už s batohem v lese, nebo při řešení logických úloh.';

    // Odrážky (nepovinné)
    $list1 = $list1 ?? '';
    $list2 = $list2 ?? '';
    $list3 = $list3 ?? '';

    // Tlačítko (nepovinné)
    $btn_text = $btn_text ?? '';
    $btn_link = $btn_link ?? '#';

    // Převrácení rozložení (fotka napravo místo nalevo)
    $isReversed = isset($reverse) && $reverse == 'true';
@endphp


<section class="py-5 rounded-4 my-5">
    <div class="container">
        <div class="row align-items-center {{ $isReversed ? 'flex-row-reverse' : '' }} g-5">

            {{-- Levý/Pravý sloupec s obrázkem --}}
            <div class="col-lg-6">
                <div
                    class="position-relative p-2 bg-white rounded-4 shadow-sm border border-secondary border-opacity-10">
                    <img src="{{ $img }}" alt="{{ $title }}" class="img-fluid rounded-4 w-100"
                        style="object-fit: cover; max-height: 500px;">

                    {{-- Jemný dekorativní prvek přesunutý pod fotku v barvě Info --}}
                    <div class="position-absolute top-100 start-0 translate-middle z-n1 d-none d-lg-block bg-info bg-opacity-10"
                        style="width: 150px; height: 150px; border-radius: 50%;"></div>
                </div>
            </div>

            {{-- Levý/Pravý sloupec s textem --}}
            <div class="col-lg-6 px-lg-4">

                {{-- Elegantní štítek s linkou --}}
                @if ($badge)
                    <div
                        class="text-info small text-uppercase tracking-wider fw-bold mb-3 d-inline-flex align-items-center">
                        <hr class="d-inline-block border-info border-1 opacity-50 my-0 me-2" style="width: 24px;">
                        {{ $badge }} 
                        <hr class="d-inline-block border-info border-1 opacity-50 my-0 ms-2" style="width: 24px;">
                    </div>
                @endif

                <h2 class="display-5 fw-bold text-dark mb-4 lh-sm">{{ $title }}</h2>
                <p class="lead text-muted mb-4 lh-base">{{ $text }}</p>

                {{-- Generování odrážek (zabalené do čistých boxíků) --}}
                @if ($list1 || $list2 || $list3)
                    <ul class="list-unstyled mb-5 d-flex flex-column gap-3">
                        @if ($list1)
                            <li
                                class="d-flex align-items-center bg-light p-3 rounded-4 shadow-sm border border-secondary border-opacity-10">
                                <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="fw-medium text-dark">{{ $list1 }}</span>
                            </li>
                        @endif
                        @if ($list2)
                            <li
                                class="d-flex align-items-center bg-light p-3 rounded-4 shadow-sm border border-secondary border-opacity-10">
                                <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="fw-medium text-dark">{{ $list2 }}</span>
                            </li>
                        @endif
                        @if ($list3)
                            <li
                                class="d-flex align-items-center bg-light p-3 rounded-4 shadow-sm border border-secondary border-opacity-10">
                                <div class="bg-white text-info rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0 shadow-sm"
                                    style="width: 40px; height: 40px;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                                <span class="fw-medium text-dark">{{ $list3 }}</span>
                            </li>
                        @endif
                    </ul>
                @endif

                {{-- Generování tlačítka --}}
                @if ($btn_text)
                    <a href="{{ $btn_link }}"
                        class="btn btn-info text-white rounded-pill px-4 py-2 shadow-sm fw-medium transition">
                        {{ $btn_text }} <i class="fa-solid fa-arrow-right ms-2"></i>
                    </a>
                @endif
            </div>

        </div>
    </div>
</section>
