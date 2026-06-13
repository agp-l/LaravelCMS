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

                        <div class="mb-4">
                            <label for="name" class="form-label fw-bold text-muted small text-uppercase">Název aktivity</label>
                            <input type="text" class="form-control bg-light border-0 py-2" id="name" name="name" 
                                value="{{ old('name', $activity->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-muted small text-uppercase">Krátký popisek</label>
                            <textarea class="form-control bg-light border-0" id="description" name="description" rows="2">{{ old('description', $activity->description) }}</textarea>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="price_per_hour" class="form-label fw-bold text-muted small text-uppercase">Cena za hodinu (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2" id="price_per_hour" name="price_per_hour" 
                                    value="{{ old('price_per_hour', $activity->price_per_hour ?? 0) }}" min="0" required>
                            </div>
                            <div class="col-md-6">
                                <label for="price_per_day" class="form-label fw-bold text-muted small text-uppercase">Celodenní paušál (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2" id="price_per_day" name="price_per_day" 
                                    value="{{ old('price_per_day', $activity->price_per_day ?? 0) }}" min="0" required>
                            </div>
                        </div>

                      {{-- NOVÁ SEKCE PRO NASTAVENÍ DNŮ A ČASŮ --}}
                        <div class="bg-light p-4 rounded-3 mb-4 border">
                            <h6 class="fw-bold mb-3"><i class="fa-regular fa-calendar-days me-2"></i>Kdy aktivita probíhá?</h6>
                            
                            @php
                                $selectedDays = $activity->exists ? $activity->scheduleRules->pluck('day_of_week')->toArray() : [];
                                
                                // Vytáhneme unikátní časové bloky
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
                                                {{ in_array($val, old('days', $selectedDays)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="day_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-dark small text-uppercase">1. Časový blok (např. Dopoledne)</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="time" class="form-control border-0" name="start_time" value="{{ old('start_time', $startTime) }}" required>
                                        <span class="text-muted fw-bold">-</span>
                                        <input type="time" class="form-control border-0" name="end_time" value="{{ old('end_time', $endTime) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold text-muted small text-uppercase">2. Časový blok <span class="fw-normal">(Nepovinné)</span></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <input type="time" class="form-control border-0" name="start_time_2" value="{{ old('start_time_2', $startTime2) }}">
                                        <span class="text-muted fw-bold">-</span>
                                        <input type="time" class="form-control border-0" name="end_time_2" value="{{ old('end_time_2', $endTime2) }}">
                                    </div>
                                    <div class="form-text small">Pro polední pauzu. Jinak nechte prázdné.</div>
                                </div>
                            </div>
                        </div>
                        {{-- KONEC NOVÉ SEKCE --}}
                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label for="color_theme" class="form-label fw-bold text-muted small text-uppercase">Hlavní barva (HEX)</label>
                                <input type="color" class="form-control form-control-color border-0 p-1" id="color_theme" name="color_theme" 
                                    value="{{ old('color_theme', $activity->color_theme ?? '#059669') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="icon" class="form-label fw-bold text-muted small text-uppercase">Ikona (Třída)</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="icon" name="icon" 
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