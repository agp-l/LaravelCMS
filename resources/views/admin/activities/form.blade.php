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
                                value="{{ old('name', $activity->name) }}" required placeholder="Např. Kurz programování PC her">
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label fw-bold text-muted small text-uppercase">Krátký popisek (pro rezervační kartu)</label>
                            <textarea class="form-control bg-light border-0" id="description" name="description" rows="2" 
                                placeholder="Např. Technika i soustředění...">{{ old('description', $activity->description) }}</textarea>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-6">
                                <label for="price_per_hour" class="form-label fw-bold text-muted small text-uppercase">Cena za hodinu (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2" id="price_per_hour" name="price_per_hour" 
                                    value="{{ old('price_per_hour', $activity->price_per_hour ?? 0) }}" min="0" required>
                                <div class="form-text small">Pro dobrovolný příspěvek zadejte 0.</div>
                            </div>

                            <div class="col-md-6">
                                <label for="schedule_tag" class="form-label fw-bold text-muted small text-uppercase">Štítek rozvrhu</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="schedule_tag" name="schedule_tag" 
                                    value="{{ old('schedule_tag', $activity->schedule_tag) }}" required placeholder="Např. Každou středu a pátek">
                            </div>
                        </div>

                        <div class="row g-4 mb-5">
                            <div class="col-md-6">
                                <label for="color_theme" class="form-label fw-bold text-muted small text-uppercase">Hlavní barva (HEX)</label>
                                <div class="d-flex align-items-center gap-2 bg-light p-2 rounded-3">
                                    <input type="color" class="form-control form-control-color border-0 bg-transparent p-0 m-0" id="color_theme" name="color_theme" 
                                        value="{{ old('color_theme', $activity->color_theme ?? '#059669') }}" title="Vyberte barvu">
                                    <span class="text-muted small">Vyberte odstín karty</span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="icon" class="form-label fw-bold text-muted small text-uppercase">Ikona (Třída)</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="icon" name="icon" 
                                    value="{{ old('icon', $activity->icon ?? 'fa-solid fa-puzzle-piece') }}" required placeholder="fa-solid fa-laptop-code">
                                <div class="form-text small">Zkopírujte název třídy z FontAwesome.</div>
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