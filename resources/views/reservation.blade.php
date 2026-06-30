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

        {{-- Pokud je jakákoliv chyba, zobrazíme i hlavní upozornění nahoře --}}
       {{-- Pokud je jakákoliv chyba, zobrazíme i hlavní upozornění nahoře --}}
        @if ($errors->any())
            <div class="alert alert-danger mb-4 shadow-sm border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="fa-solid fa-triangle-exclamation fs-3 me-3"></i>
                    <div>
                        <strong class="d-block fs-5">Něco se nepovedlo!</strong>
                        <ul class="mb-0 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success_msg'))
            <div class="mb-5 text-center fade-in">
                {!! session('success_msg') !!}

                <a href="{{ route('reservation.index') }}" class="btn btn-dark btn-lg mt-4 shadow-sm"
                    style="border-radius: 50px; padding: 10px 30px;">
                    <i class="fa-solid fa-arrow-rotate-left me-2"></i> Zadat další rezervaci
                </a>
            </div>
        @endif

        {{-- PŘEDÁNÍ STARÝCH DAT (PŘI CHYBĚ) DO JAVASCRIPTU --}}
        <script>
            window.ServerOldState = {
                activity: "{{ old('activity_id') }}",
                date: "{{ old('reservation_date') }}",
                slots: {!! json_encode(old('slot', [])) !!},
                kidsCount: "{{ old('kidsCount') }}",
                sharing: "{{ old('sharing') }}",
                pricing: "{{ old('pricing') }}"
            };
        </script>

        <form method="POST" action="{{ route('reservation.store') }}">
            @csrf

            <div class="step-container">
                <h3 class="step-title"><span class="step-number">1</span> Vyberte aktivitu</h3>

                <div class="row g-4">
                    @foreach ($activities as $index => $activity)
                        <div class="col-md-6 col-lg-4">
                            <label class="w-100 h-100">
                                <input type="radio" name="activity_id" value="{{ $activity->id }}"
                                    class="d-none activity-radio" data-monthly-mode="{{ $activity->monthly_pass_mode }}"
                                    data-color="{{ $activity->color_theme }}" data-price="{{ $activity->price_per_hour }}"
                                    data-price-day="{{ $activity->price_per_day }}"
                                    data-price-month="{{ $activity->price_per_month }}"
                                    data-max-capacity="{{ $activity->max_capacity }}"
                                    data-booking-mode="{{ $activity->booking_mode }}"
                                    data-pricing-model="{{ $activity->pricing_model }}"
                                    data-show-child-name="{{ $activity->show_child_name }}"
                                    data-show-kids-count="{{ $activity->show_kids_count }}"
                                    data-show-child-info="{{ $activity->show_child_info }}"
                                    data-show-note="{{ $activity->show_note }}"
                                    data-custom-label="{{ $activity->custom_field_label }}"
                                    data-custom-required="{{ $activity->custom_field_required }}"
                                    data-days="{{ json_encode($activity->scheduleRules->whereNull('date_override')->pluck('day_of_week')->toArray()) }}"
                                    {{ old('activity_id') == $activity->id || (empty(old('activity_id')) && $index === 0) ? 'checked' : '' }}>
                                <div class="activity-card {{ old('activity_id') == $activity->id || (empty(old('activity_id')) && $index === 0) ? 'active' : '' }}"
                                    style="--theme-color: {{ $activity->color_theme }};">

                                    <div class="activity-price-tag">
                                        @if ($activity->pricing_model === 'monthly')
                                            {{ number_format($activity->price_per_month, 0, ',', ' ') }} Kč / měsíc
                                        @elseif ($activity->pricing_model === 'daily')
                                            {{ number_format($activity->price_per_day, 0, ',', ' ') }} Kč / den
                                        @elseif ($activity->price_per_hour > 0)
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

                @error('reservation_date')
                    <div class="alert alert-danger py-2 small fw-bold mb-3"><i
                            class="fa-solid fa-circle-exclamation me-2"></i>Musíte vybrat platné datum!</div>
                @enderror

                <div class="d-flex overflow-auto gap-2 pb-2 hide-scroll" id="calDaysContainer">
                    {{-- Sem sype dny Javascript --}}
                </div>

                <div class="d-flex justify-content-center gap-3 mt-3">
                    <button type="button"
                        class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm"
                        id="scrollPrev" style="width: 45px; height: 45px; background: #f8fafc;">
                        <i class="fa-solid fa-chevron-left" style="color: #64748b;"></i>
                    </button>
                    <button type="button"
                        class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-0 shadow-sm"
                        id="scrollNext" style="width: 45px; height: 45px; background: #f8fafc;">
                        <i class="fa-solid fa-chevron-right" style="color: #64748b;"></i>
                    </button>
                </div>

            </div>

            <div class="step-container" style="--theme-color: #059669;" id="slots-step">
                <h3 class="step-title"><span class="step-number">3</span> Volné hodiny a údaje</h3>

                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0 border-end pe-md-4">
                        <h5 class="fw-bold mb-3 fs-6 text-muted text-uppercase">Dostupné časy</h5>

                        {{-- Změnili jsme type="hidden" na type="text" + d-none, a přidali atributy, které odhání správce hesel --}}
                        <input type="text" name="reservation_date" id="selectedDateOutput"
                            value="{{ old('reservation_date') }}" class="d-none" readonly autocomplete="off"
                            data-lpignore="true" data-form-type="other">
                        <input type="text" name="recurring_days" id="recurringDaysOutput"
                            value="{{ old('recurring_days') }}" class="d-none" readonly autocomplete="off"
                            data-lpignore="true" data-form-type="other">

                        @error('slot')
                            <div class="alert alert-danger py-2 small fw-bold mb-3"><i
                                    class="fa-solid fa-circle-exclamation me-2"></i>Musíte vybrat alespoň jeden časový blok!
                            </div>
                        @enderror

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

                        {{-- Jméno dítěte --}}
                        <div class="mb-3" id="wrap-child-name">
                            <label class="form-label fw-bold small">Jméno dítěte</label>
                            <input type="text"
                                class="form-control bg-light border-0 @error('child_name') is-invalid @enderror"
                                id="input-child-name" name="child_name" value="{{ old('child_name') }}">
                            @error('child_name')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Info o dítěti --}}
                        <div class="mb-3" id="wrap-child-info">
                            <label class="form-label fw-bold small">Věk dětí <span
                                    class="text-muted fw-normal">(nepovinné)</span></label>
                            <input type="text"
                                class="form-control bg-light border-0 @error('child_info') is-invalid @enderror"
                                id="input-child-info" name="child_info" placeholder="Např. 8 a 10 let"
                                value="{{ old('child_info') }}">
                            @error('child_info')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            {{-- Počet dětí --}}
                            <div class="col-6 mb-3" id="wrap-kids-count">
                                <label class="form-label fw-bold small">Počet dětí</label>
                                <select class="form-select bg-light border-0 @error('kidsCount') is-invalid @enderror"
                                    id="input-kids-count" name="kidsCount"></select>
                                @error('kidsCount')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Podoba setkání --}}
                            <div class="col-md-6 mb-3" id="wrap-sharing">
                                <label class="form-label fw-bold small">Podoba setkání</label>
                                <select class="form-select bg-light border-0 @error('sharing') is-invalid @enderror"
                                    id="input-sharing" name="sharing"></select>
                                @error('sharing')
                                    <div class="invalid-feedback fw-bold">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Cenový model --}}
                        <div class="mb-3" id="wrap-pricing">
                            <label class="form-label fw-bold small">Model účtování</label>
                            <select class="form-select bg-light border-0 @error('pricing') is-invalid @enderror"
                                id="input-pricing" name="pricing"></select>
                            @error('pricing')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Jméno rodiče --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Jméno rodiče</label>
                            <input type="text"
                                class="form-control bg-light border-0 @error('parent_name') is-invalid @enderror"
                                name="parent_name" value="{{ old('parent_name') }}" required>
                            @error('parent_name')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kontakt --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Kontakt (E-mail / Telefon)</label>
                            <input type="text"
                                class="form-control bg-light border-0 @error('contact') is-invalid @enderror"
                                name="contact" value="{{ old('contact') }}" required>
                            @error('contact')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Poznámka --}}
                        <div class="mb-4" id="wrap-note">
                            <label class="form-label fw-bold small">Poznámka <span
                                    class="text-muted fw-normal">(nepovinné)</span></label>
                            <textarea class="form-control bg-light border-0 @error('note') is-invalid @enderror" id="input-note" name="note"
                                rows="2" placeholder="Jakékoliv specifikum, které bych měl vědět...">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Vlastní políčko --}}
                        <div class="mb-4 d-none" id="wrap-custom-field">
                            <label class="form-label fw-bold small" id="label-custom-field">Vlastní políčko</label>
                            <input type="text"
                                class="form-control bg-light border-0 @error('custom_field') is-invalid @enderror"
                                id="input-custom-field" name="custom_field" value="{{ old('custom_field') }}">
                            @error('custom_field')
                                <div class="invalid-feedback fw-bold">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-lg w-100 text-white fw-bold" id="submit-btn"
                            style="background: var(--theme-color, #059669);">Dokončit rezervaci <i
                                class="fa-solid fa-arrow-right ms-2"></i></button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script src="{{ asset('js/reservation.js') }}?v={{ time() }}"></script>

@endsection
