@extends('layouts.default.app')

@section('title', 'Nová rezervace')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/reservation.css') }}">

    <div class="container py-5">

        <div class="text-center mb-5">
            <span class="badge bg-dark mb-2 px-3 py-2">Dům Svobodného přístavu</span>
            <h1 class="display-5 fw-bold">Rezervace aktivit</h1>
            <p class="text-muted lead">Vyberte si aktivitu, termín a přidejte se k nám.</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <strong>Chyba:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ZDE JE VLOŽENÝ CHYBĚJÍCÍ BLOK PRO ÚSPĚŠNOU REZERVACI A QR KÓD --}}
@if(session('success_msg'))
            <div class="mb-5 text-center fade-in">
                {!! session('success_msg') !!}
                
                <a href="{{ route('reservation.index') }}" class="btn btn-dark btn-lg mt-4 shadow-sm" style="border-radius: 50px; padding: 10px 30px;">
                    <i class="fa-solid fa-arrow-rotate-left me-2"></i> Zadat další rezervaci
                </a>
            </div>
        @endif
        {{-- KONEC VLOŽENÉHO BLOKU --}}

        <form method="POST" action="{{ route('reservation.store') }}">
            @csrf

            <div class="step-container">
                <h3 class="step-title"><span class="step-number">1</span> Vyberte aktivitu</h3>

                <div class="row g-4">
                    @foreach ($activities as $index => $activity)
                        <div class="col-md-6 col-lg-4">
                            <label class="w-100 h-100">
                                <input type="radio" name="activity_id" value="{{ $activity->id }}"
                                    class="d-none activity-radio" data-color="{{ $activity->color_theme }}"
                                    data-price="{{ $activity->price_per_hour }}"
                                    data-days="{{ json_encode($activity->scheduleRules->whereNull('date_override')->pluck('day_of_week')->toArray()) }}"
                                    {{ $index === 0 ? 'checked' : '' }}>
                                <div class="activity-card {{ $index === 0 ? 'active' : '' }}"
                                    style="--theme-color: {{ $activity->color_theme }};">

                                    <div class="activity-price-tag">
                                        @if ($activity->price_per_hour > 0)
                                            {{ number_format($activity->price_per_hour, 0, ',', ' ') }} Kč / h
                                        @else
                                            V ceně
                                        @endif
                                    </div>

                                    <i class="{{ $activity->icon }} activity-icon"
                                        style="color: {{ $activity->color_theme }};"></i>
                                    <h4 class="fw-bold fs-5">{{ $activity->name }}</h4>
                                    <p class="text-muted small mb-0">{{ $activity->description }}</p>

                                    <div class="activity-schedule-tag mt-3"
                                        style="background: {{ $activity->color_theme }}15; color: {{ $activity->color_theme }};">
                                        <i class="fa-regular fa-clock me-1"></i> {{ $activity->schedule_tag }}
                                    </div>

                                </div>
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

           <div class="step-container" style="--theme-color: #059669;" id="calendar-step">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="step-title mb-0"><span class="step-number">2</span> Zvolte datum</h3>
                    <div class="text-muted fw-bold text-end" id="calMonthText">
                        <i class="fa-regular fa-calendar me-2"></i> Načítám...
                    </div>
                </div>

                <div class="d-flex overflow-auto gap-2 pb-2 hide-scroll" id="calDaysContainer">
                    {{-- Sem sype dny Javascript --}}
                </div>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button type="button" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm" id="scrollPrev" style="width: 45px; height: 45px; background: #f8fafc;">
                        <i class="fa-solid fa-chevron-left" style="color: #64748b;"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm" id="scrollNext" style="width: 45px; height: 45px; background: #f8fafc;">
                        <i class="fa-solid fa-chevron-right" style="color: #64748b;"></i>
                    </button>
                </div>

            </div>

            <div class="step-container" style="--theme-color: #059669;" id="slots-step">
                <h3 class="step-title"><span class="step-number">3</span> Volné hodiny a údaje</h3>

                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0 border-end pe-md-4">
                        <h5 class="fw-bold mb-3 fs-6 text-muted text-uppercase">Dostupné časy</h5>
                        
                        {{-- Skrytý input pro odeslání data do Laravelu --}}
                        <input type="hidden" name="reservation_date" id="selectedDateOutput">

                        <div class="row g-2" id="dynamicSlotsContainer">
                            {{-- Sem sype hodiny Javascript --}}
                        </div>

                        <div class="mt-4 p-3 bg-light rounded-3 text-center">
                            <span class="text-muted d-block small fw-bold text-uppercase mb-1">Doporučený příspěvěk</span>
                            <span class="fs-3 fw-bold text-dark" id="calculated-price">0 Kč</span>
                        </div>
                    </div>

                    <div class="col-md-6 ps-md-4">
                        <h5 class="fw-bold mb-3 fs-6 text-muted text-uppercase">Vaše údaje</h5>
                        
                        <div class="mb-3"><label class="form-label fw-bold small">Jméno dítěte</label><input
                                type="text" class="form-control bg-light border-0" name="child_name" required></div>
                        
                        <div class="row">
                            <div class="col-6 mb-3"><label class="form-label fw-bold small">Počet dětí</label><select
                                    class="form-select bg-light border-0" name="kidsCount">
                                    <option value="1">1 dítě</option>
                                    <option value="2">2 děti</option>
                                </select></div>
                            <div class="col-6 mb-3"><label class="form-label fw-bold small">Sdílení</label><select
                                    class="form-select bg-light border-0" name="sharing">
                                    <option value="Individuální čas">Soukromě</option>
                                    <option value="Sdílený čas">Otevřená skupina</option>
                                </select></div>
                        </div>

                        {{-- Přidán chybějící select pro pricing, který vyžaduje JS i Controller --}}
                        <div class="mb-3"><label class="form-label fw-bold small">Typ parťáka (Cena)</label><select
                                class="form-select bg-light border-0" name="pricing">
                                <option value="Parťák na hodinu">Základní (dle hodin)</option>
                                <option value="Celodenní parťák">Celodenní (fix 1500 Kč)</option>
                            </select></div>

                        <div class="mb-3"><label class="form-label fw-bold small">Jméno rodiče</label><input
                                type="text" class="form-control bg-light border-0" name="parent_name" required></div>
                        <div class="mb-4"><label class="form-label fw-bold small">Kontakt (E-mail /
                                Telefon)</label><input type="text" class="form-control bg-light border-0"
                                name="contact" required></div>

                        <button type="submit" class="btn btn-lg w-100 text-white fw-bold" id="submit-btn"
                            style="background: var(--theme-color, #059669);">Dokončit rezervaci <i
                                class="fa-solid fa-arrow-right ms-2"></i></button>
                    </div>
                </div>
            </div>

        </form>
    </div>
    
    {{-- Správně napojený JavaScript s cache busterem --}}
    <script src="{{ asset('js/reservation.js') }}?v={{ time() }}"></script>

@endsection