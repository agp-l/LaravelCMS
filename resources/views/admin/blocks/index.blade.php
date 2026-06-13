@extends($layout ?? 'layouts.default.app')

@section('title', 'Dispečink a výluky')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0 fw-bold text-dark">Dispečink a mimořádné výluky</h3>
            <p class="text-muted small mb-0">Zablokování dnů (nemoc, dovolená) a pozastavení aktivit</p>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success rounded-3 mb-4 shadow-sm">
            <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

<div class="row g-4">
    {{-- LEVÝ SLOUPEC: Globální blokace dnů --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-danger mb-0"><i class="fa-solid fa-calendar-xmark me-2"></i>Zablokované dny</h5>
                <p class="small text-muted mt-1">V tyto dny se nepůjde zarezervovat na ŽÁDNOU aktivitu.</p>
            </div>
            <div class="card-body p-4">
                
                {{-- Formulář pro přidání nového dne volna --}}
                <form action="{{ route('admin.blocks.store_day') }}" method="POST" class="bg-light p-3 rounded-3 mb-4 border">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label fw-bold small text-uppercase text-muted">Vyberte datum</label>
                            <input type="date" class="form-control border-0" name="blocked_date" required min="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-danger w-100 fw-bold">Zablokovat</button>
                        </div>
                    </div>
                </form>

                {{-- Výpis aktuálně zablokovaných dnů --}}
                <ul class="list-group list-group-flush">
                    @forelse($blockedDays as $day)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <span class="fw-bold text-dark">{{ \Carbon\Carbon::parse($day->date_override)->format('d. m. Y') }}</span>
                                @if(\Carbon\Carbon::parse($day->date_override)->isPast())
                                    <span class="badge bg-secondary ms-2">Proběhlo</span>
                                @endif
                            </div>
                            <form action="{{ route('admin.blocks.destroy_day', $day->id) }}" method="POST">
                                @csrf @method('DELETE')
                                {{-- OPRAVENÉ TLAČÍTKO --}}
                                <button class="btn btn-outline-secondary rounded-circle" title="Zrušit blokaci" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </form>
                        </li>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="fa-solid fa-mug-hot fs-4 mb-2 opacity-50"></i><br>
                            Žádné zablokované dny.<br>Pracujete naplno.
                        </div>
                    @endforelse
                </ul>

            </div>
        </div>
    </div>

    {{-- PRAVÝ SLOUPEC: Aktivace / Deaktivace kroužků --}}
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <h5 class="fw-bold text-warning mb-0"><i class="fa-solid fa-power-off me-2"></i>Stav aktivit</h5>
                <p class="small text-muted mt-1">Skrytí aktivity pro veřejnost (rezervace v administraci zůstanou).</p>
            </div>
            <div class="card-body p-4">
                
                <ul class="list-group list-group-flush">
                    @foreach($activities as $act)
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-3">
                            <div>
                                <i class="{{ $act->icon }} me-2" style="color: {{ $act->color_theme }}"></i>
                                <span class="fw-bold text-dark">{{ $act->name }}</span>
                            </div>
                            
                            {{-- Rychlý formulář pro přepnutí stavu --}}
                            <form action="{{ route('admin.blocks.toggle_activity', $act->id) }}" method="POST">
                                @csrf
                                @if($act->is_active)
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3">
                                        <i class="fa-solid fa-eye me-1"></i> Aktivní
                                    </button>
                                @else
                                    <button type="submit" class="btn btn-sm btn-secondary rounded-pill px-3">
                                        <i class="fa-solid fa-eye-slash me-1"></i> Pozastaveno
                                    </button>
                                @endif
                            </form>
                        </li>
                    @endforeach
                </ul>

            </div>
        </div>
    </div>
</div>
</div>
@endsection