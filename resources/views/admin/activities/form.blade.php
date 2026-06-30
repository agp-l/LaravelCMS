@extends($layout ?? 'layouts.default.app')

@section('title', $activity->exists ? 'Upravit aktivitu' : 'Nová aktivita')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.activities.index') }}" class="btn btn-outline-secondary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="mb-0 fw-bold text-dark">{{ $activity->exists ? 'Úprava aktivity' : 'Vytvořit novou aktivitu' }}</h3>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <strong class="d-block mb-2">Některá pole nejsou správně vyplněna!</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ $activity->exists ? route('admin.activities.update', $activity->id) : route('admin.activities.store') }}" method="POST">
                        @csrf
                        @if($activity->exists)
                            @method('PUT')
                        @endif

                        <div class="p-3 mb-5 rounded-3 border" style="background-color: #f8fafc;">
                            <div class="form-check form-switch fs-5">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $activity->is_active ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="is_active">
                                    Aktivita je spuštěna a přijímá rezervace
                                </label>
                            </div>
                            <small class="text-muted d-block mt-1 ms-5">Pokud onemocníš, stačí toto vypnout. Aktivita zmizí z nabídky a v kalendáři se zablokuje.</small>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4"><i class="fa-solid fa-info-circle me-2 text-primary"></i>Základní informace</h5>

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-muted small text-uppercase">Název aktivity</label>
                            <input type="text" class="form-control bg-light border-0 py-2 @error('name') is-invalid @enderror" id="name" name="name" 
                                value="{{ old('name', $activity->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-muted small text-uppercase">Krátký popisek</label>
                            <textarea class="form-control bg-light border-0 @error('description') is-invalid @enderror" id="description" name="description" rows="2">{{ old('description', $activity->description) }}</textarea>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i class="fa-solid fa-gears me-2 text-primary"></i>Nastavení rezervačního systému</h5>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label for="max_capacity" class="form-label fw-bold text-muted small text-uppercase">Max. kapacita dětí</label>
                                <input type="number" name="max_capacity" class="form-control bg-light border-0 py-2 @error('max_capacity') is-invalid @enderror" id="max_capacity" 
                                    value="{{ old('max_capacity', $activity->max_capacity ?? 5) }}" min="1" required>
                                <div class="form-text small">Individuální setkání = 1</div>
                            </div>
                            <div class="col-md-4">
                                <label for="booking_mode" class="form-label fw-bold text-muted small text-uppercase">Podoba setkání</label>
                                <select name="booking_mode" class="form-select bg-light border-0 py-2 @error('booking_mode') is-invalid @enderror" id="booking_mode" required>
                                    <option value="both" {{ old('booking_mode', $activity->booking_mode) == 'both' ? 'selected' : '' }}>Obojí (Individuální i Parta)</option>
                                    <option value="individual" {{ old('booking_mode', $activity->booking_mode) == 'individual' ? 'selected' : '' }}>Pouze Individuální</option>
                                    <option value="shared" {{ old('booking_mode', $activity->booking_mode) == 'shared' ? 'selected' : '' }}>Pouze Otevřená parta</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="pricing_model" class="form-label fw-bold text-muted small text-uppercase">Model účtování</label>
                                <select name="pricing_model" class="form-select bg-light border-0 py-2 @error('pricing_model') is-invalid @enderror" id="pricing_model" required>
                                    <option value="hourly" {{ old('pricing_model', $activity->pricing_model) == 'hourly' ? 'selected' : '' }}>Od hodiny</option>
                                    <option value="daily" {{ old('pricing_model', $activity->pricing_model) == 'daily' ? 'selected' : '' }}>Denní paušál</option>
                                    <option value="monthly" {{ old('pricing_model', $activity->pricing_model) == 'monthly' ? 'selected' : '' }}>Měsíční paušál</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="monthly_pass_mode" class="form-label fw-bold text-muted small text-uppercase">Režim Měsíčního paušálu</label>
                                <select name="monthly_pass_mode" class="form-select bg-light border-0 py-2 @error('monthly_pass_mode') is-invalid @enderror" id="monthly_pass_mode" required>
                                    <option value="single_day" {{ old('monthly_pass_mode', $activity->monthly_pass_mode ?? 'single_day') == 'single_day' ? 'selected' : '' }}>Pouze jeden den v týdnu (př. RC Kroužek JEN v Pondělí)</option>
                                    <option value="all_days" {{ old('monthly_pass_mode', $activity->monthly_pass_mode ?? '') == 'all_days' ? 'selected' : '' }}>Všechny vypsané dny (př. Lesní školka Po-Pá)</option>
                                </select>
                                <div class="form-text small text-primary fw-bold">Pokud dítě chodí jen např. na úterky, zvolte "Pouze jeden den". Kroužek můžete nabízet klidně 5 dní v týdnu, ale rodiči se zarezervují jen ta úterý!</div>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-4">
                                <label for="price_per_hour" class="form-label fw-bold text-muted small text-uppercase">Cena za hodinu (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2 @error('price_per_hour') is-invalid @enderror" id="price_per_hour" name="price_per_hour" 
                                    value="{{ old('price_per_hour', $activity->price_per_hour ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="price_per_day" class="form-label fw-bold text-muted small text-uppercase">Celodenní paušál (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2 @error('price_per_day') is-invalid @enderror" id="price_per_day" name="price_per_day" 
                                    value="{{ old('price_per_day', $activity->price_per_day ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-4">
                                <label for="price_per_month" class="form-label fw-bold text-muted small text-uppercase">Měsíční paušál (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2 @error('price_per_month') is-invalid @enderror" id="price_per_month" name="price_per_month" 
                                    value="{{ old('price_per_month', $activity->price_per_month ?? 0) }}" min="0">
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i class="fa-regular fa-calendar-days me-2 text-primary"></i>Kdy aktivita probíhá?</h5>
                        <div class="bg-light p-4 rounded-3 mb-4 border @error('days') border-danger bg-danger bg-opacity-10 @enderror">
                            
                            @php
                                $selectedDays = $activity->exists ? $activity->scheduleRules->pluck('day_of_week')->toArray() : [];
                                
                                $blocks = $activity->exists ? $activity->scheduleRules->whereNull('date_override')->unique(function($item) {
                                    return $item->start_time . '-' . $item->end_time;
                                })->values() : collect([]);

                                $startTime = isset($blocks[0]) ? \Carbon\Carbon::parse($blocks[0]->start_time)->format('H:i') : '09:00';
                                $endTime = isset($blocks[0]) ? \Carbon\Carbon::parse($blocks[0]->end_time)->format('H:i') : '12:00';
                                
                                $startTime2 = isset($blocks[1]) ? \Carbon\Carbon::parse($blocks[1]->start_time)->format('H:i') : '';
                                $endTime2 = isset($blocks[1]) ? \Carbon\Carbon::parse($blocks[1]->end_time)->format('H:i') : '';
                            @endphp

                            <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Dny v týdnu</label>
                                <div class="d-flex flex-wrap gap-3">
                                    @php $daysList = [1 => 'Pondělí', 2 => 'Úterý', 3 => 'Středa', 4 => 'Čtvrtek', 5 => 'Pátek', 6 => 'Sobota', 0 => 'Neděle']; @endphp
                                    @foreach($daysList as $val => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="days[]" value="{{ $val }}" id="day_{{ $val }}" 
                                                {{ (is_array(old('days', $selectedDays)) && in_array($val, old('days', $selectedDays))) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small text-uppercase">1. Časový blok (např. Dopoledne)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="time" class="form-control border-0 @error('start_time') is-invalid @enderror" name="start_time" value="{{ old('start_time', $startTime) }}" required>
                                        <span class="text-muted fw-bold">-</span>
                                        <input type="time" class="form-control border-0 @error('end_time') is-invalid @enderror" name="end_time" value="{{ old('end_time', $endTime) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">2. Časový blok <span class="fw-normal">(Nepovinné)</span></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="time" class="form-control border-0 @error('start_time_2') is-invalid @enderror" name="start_time_2" value="{{ old('start_time_2', $startTime2) }}">
                                        <span class="text-muted fw-bold">-</span>
                                        <input type="time" class="form-control border-0 @error('end_time_2') is-invalid @enderror" name="end_time_2" value="{{ old('end_time_2', $endTime2) }}">
                                    </div>
                                    <div class="form-text small">Pro polední pauzu. Jinak nechte prázdné.</div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i class="fa-solid fa-eye me-2 text-primary"></i>Viditelnost formuláře (Rezervace)</h5>
                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_child_name" id="show_child_name" value="1" {{ old('show_child_name', $activity->show_child_name ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_child_name">Vyžadovat jméno dítěte</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_kids_count" id="show_kids_count" value="1" {{ old('show_kids_count', $activity->show_kids_count ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_kids_count">Zobrazit výběr počtu dětí</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_child_info" id="show_child_info" value="1" {{ old('show_child_info', $activity->show_child_info ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_child_info">Zobrazit doplňující info (věk atd.)</label>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="show_note" id="show_note" value="1" {{ old('show_note', $activity->show_note ?? true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="show_note">Zobrazit pole pro poznámku</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light border-0 shadow-sm">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold text-muted small text-uppercase">Vlastní políčko (Náhradní)</h6>
                                        <div class="mb-3 mt-3">
                                            <label class="form-label small">Název políčka (nechte prázdné pro skrytí)</label>
                                            <input type="text" name="custom_field_label" class="form-control form-control-sm border-0 py-2 @error('custom_field_label') is-invalid @enderror" value="{{ old('custom_field_label', $activity->custom_field_label) }}" placeholder="Např. Zdravotní omezení">
                                        </div>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="custom_field_required" id="custom_field_required" value="1" {{ old('custom_field_required', $activity->custom_field_required ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="custom_field_required">Je toto políčko povinné?</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i class="fa-solid fa-palette me-2 text-primary"></i>Vzhled karty</h5>
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label for="color_theme" class="form-label fw-bold text-muted small text-uppercase">Hlavní barva (HEX)</label>
                                <input type="color" class="form-control form-control-color border-0 p-1 @error('color_theme') is-invalid @enderror" id="color_theme" name="color_theme" 
                                    value="{{ old('color_theme', $activity->color_theme ?? '#059669') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="icon" class="form-label fw-bold text-muted small text-uppercase">Ikona (Třída FontAwesome)</label>
                                <input type="text" class="form-control bg-light border-0 py-2 @error('icon') is-invalid @enderror" id="icon" name="icon" 
                                    value="{{ old('icon', $activity->icon ?? 'fa-solid fa-puzzle-piece') }}" required>
                            </div>
                        </div>

                        <div class="text-end border-top pt-4">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm rounded-pill px-5 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> {{ $activity->exists ? 'Uložit změny' : 'Vytvořit aktivitu' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection