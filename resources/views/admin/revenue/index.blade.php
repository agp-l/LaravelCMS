@extends($layout ?? 'layouts.default.app')

@section('title', 'Finanční přehled')

@section('content')
<div class="container py-5" style="font-family: 'Inter', sans-serif;">
    
    {{-- Elegantní záhlaví sekce --}}
    <div class="d-flex align-items-center mb-5 pb-4 border-bottom">
        <div style="background: #059669; width: 56px; height: 64px; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(5, 150, 105, 0.15);" class="me-4">
            <i class="fa-solid fa-calculator text-white fs-4"></i>
        </div>
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Finanční analýza a potenciál</h2>
            <p class="text-muted mb-0 small">Sledujte reálné tržby z objednávek a simulujte měsíční výtěžnost kapacit</p>
        </div>
    </div>

    <div class="row g-5">
        
        {{-- LEVÝ SLOUPEC: REÁLNÝ PŘEHLED (DATA Z OBJEDNÁVEK) --}}
        <div class="col-lg-5">
            <h5 class="fw-bold mb-4 text-secondary text-uppercase small letter-spacing-1"><i class="fa-solid fa-chart-line me-2 text-success"></i> Skutečné tržby po měsících</h5>
            
            @if(empty($monthlyStats))
                <div class="card border-0 bg-light rounded-4 text-center py-5 border">
                    <i class="fa-solid fa-receipt display-5 text-muted opacity-25 mb-3"></i>
                    <h6 class="fw-bold text-dark mb-1">Žádné reálné tržby</h6>
                    <p class="text-muted small mb-0">Jakmile rodiče zaplatí první rezervace, uvidíte zde grafické karty.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3">
                    @foreach($monthlyStats as $key => $stat)
                        <div class="card border border-light-subtle shadow-sm rounded-4 bg-white overflow-hidden">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-3 py-2 fw-bold small">
                                        {{ $stat['label'] }}
                                    </span>
                                    <span class="text-muted small fw-semibold"><i class="fa-solid fa-folder-open me-1"></i> {{ $stat['count'] }} obj.</span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-6 border-end">
                                        <span class="d-block text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Vybráno</span>
                                        <span class="fs-4 fw-bold text-success font-monospace">{{ number_format($stat['paid'], 0, ',', ' ') }} Kč</span>
                                    </div>
                                    <div class="col-6 ps-4">
                                        <span class="d-block text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Čeká na platbu</span>
                                        <span class="fs-4 fw-bold text-warning font-monospace">{{ number_format($stat['pending'], 0, ',', ' ') }} Kč</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- PRAVÝ SLOUPEC: INTERAKTIVNÍ KALKULAČKA POTENCIÁLU --}}
        <div class="col-lg-7">
            <div class="d-flex justify-content-between align-items-end mb-4">
                <h5 class="fw-bold mb-0 text-secondary text-uppercase small letter-spacing-1"><i class="fa-solid fa-sliders me-2 text-success"></i> Simulátor měsíčního zisku</h5>
                <button type="button" id="btn-toggle-all" class="btn btn-sm btn-outline-secondary rounded-pill px-3 fw-bold shadow-sm">
                    <i class="fa-solid fa-toggle-off me-1"></i> Odznačit vše
                </button>
            </div>
            
            {{-- Hlavní sčítací karta celkového potenciálu --}}
            <div class="card border border-light-subtle shadow-sm rounded-4 bg-white p-4 mb-4" style="border-left: 5px solid #059669 !important;">
                <div class="card-body p-2">
                    <span class="text-muted small fw-bold text-uppercase d-block mb-1" style="letter-spacing: 0.5px;">Celkový odhadovaný měsíční zisk</span>
                    <div class="display-5 fw-bold text-dark font-monospace mb-2" id="total-potential-display">0 Kč</div>
                    <p class="text-muted small mb-0 lh-base">Částka se okamžitě přepočítává podle toho, jak hýbeš s parametry u jednotlivých aktivit.</p>
                </div>
            </div>

            {{-- Výpis interaktivních karet jednotlivých aktivit --}}
            <div class="d-flex flex-column gap-3">
                @forelse($potentialData as $act)
                    <div class="card border border-light-subtle shadow-sm rounded-4 bg-white potential-card transition-all" data-model="{{ $act->pricing_model }}">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3 border-bottom pb-3">
                                <div class="d-flex align-items-center">
                                    <div style="background: {{ $act->color }}15; width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center;" class="me-3">
                                        <i class="{{ $act->icon }}" style="color: {{ $act->color }}; font-size: 1rem;"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0 fs-6">{{ $act->name }}</h6>
                                        <span class="text-muted" style="font-size: 0.75rem;">
                                            @if($act->pricing_model == 'monthly') Měsíční paušál
                                            @elseif($act->pricing_model == 'daily') Denní paušál
                                            @else Hodinová sazba @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="form-check form-switch m-0 p-0 pt-1">
                                    <input class="form-check-input activity-toggle style-switch ms-0" type="checkbox" checked style="width: 2.5em; height: 1.2em; cursor: pointer;">
                                </div>
                            </div>

                            {{-- INTERAKTIVNÍ POLÍČKA PRO SIMULACI --}}
                            <div class="row align-items-end g-2 bg-light rounded-3 p-3 mb-3 border">
                                
                                {{-- Počet dětí (všude) --}}
                                <div class="col-6 col-md-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Dětí / Kapacita</label>
                                    <input type="number" class="form-control form-control-sm border-secondary-subtle font-monospace fw-bold text-center input-kids" value="4" min="0" style="border-radius: 6px;">
                                </div>

                                {{-- Sazba (paušál nebo hodinovka) --}}
                                <div class="col-6 col-md-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Sazba (Kč)</label>
                                    @php
                                        $defaultPrice = 0;
                                        if($act->pricing_model == 'monthly') $defaultPrice = $act->price_per_month;
                                        elseif($act->pricing_model == 'daily') $defaultPrice = $act->price_per_day;
                                        else $defaultPrice = $act->price_per_hour;
                                    @endphp
                                    <input type="number" class="form-control form-control-sm border-secondary-subtle font-monospace fw-bold text-center input-price text-success" value="{{ $defaultPrice }}" min="0" style="border-radius: 6px;">
                                </div>

                                {{-- Počet dní (U paušálu to může být 1 skupina, u hodinovky to jsou dny v měsíci) --}}
                                <div class="col-6 col-md-3">
                                    <label class="form-label text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">
                                        {{ $act->pricing_model == 'monthly' ? 'Skupin/Frekvence' : 'Dní za měsíc' }}
                                    </label>
                                    <input type="number" class="form-control form-control-sm border-secondary-subtle font-monospace fw-bold text-center input-days" 
                                           value="{{ $act->pricing_model == 'monthly' ? 1 : $act->days_per_month }}" min="0" style="border-radius: 6px;">
                                </div>

                                {{-- Počet hodin (Pouze u hodinové sazby) --}}
                                @if($act->pricing_model == 'hourly')
                                    <div class="col-6 col-md-3">
                                        <label class="form-label text-muted small fw-bold text-uppercase mb-1" style="font-size: 0.65rem;">Hodin za den</label>
                                        <input type="number" class="form-control form-control-sm border-secondary-subtle font-monospace fw-bold text-center input-hours" value="{{ $act->default_hours }}" min="0" style="border-radius: 6px;">
                                    </div>
                                @endif

                            </div>

                            {{-- Dynamický výsledek za konkrétní kartu --}}
                            <div class="text-end">
                                <span class="text-muted small me-2">Výnos z této aktivity:</span>
                                <span class="fw-bold text-dark font-monospace activity-potential-text" style="font-size: 1.15rem;">0 Kč</span>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted small py-5 bg-light rounded-4 border border-dashed">
                        Zatím nemáte spuštěné žádné aktivity.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>

<style>
    .letter-spacing-1 { letter-spacing: 0.5px; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .style-switch:checked { background-color: #059669 !important; border-color: #059669 !important; }
    .potential-card.disabled-card { opacity: 0.6; background-color: #f8fafc !important; }
    .potential-card.disabled-card .input-kids,
    .potential-card.disabled-card .input-price,
    .potential-card.disabled-card .input-days,
    .potential-card.disabled-card .input-hours {
        background-color: #e9ecef;
        pointer-events: none;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const totalDisplay = document.getElementById('total-potential-display');
        const cards = document.querySelectorAll('.potential-card');
        const toggleAllBtn = document.getElementById('btn-toggle-all');
        let allSelected = true;

        // Vypnout / Zapnout vše
        if (toggleAllBtn) {
            toggleAllBtn.addEventListener('click', function() {
                allSelected = !allSelected;
                cards.forEach(card => {
                    card.querySelector('.activity-toggle').checked = allSelected;
                });
                
                if (allSelected) {
                    this.innerHTML = '<i class="fa-solid fa-toggle-off me-1"></i> Odznačit vše';
                    this.classList.remove('btn-success');
                    this.classList.add('btn-outline-secondary');
                } else {
                    this.innerHTML = '<i class="fa-solid fa-toggle-on me-1"></i> Zapnout vše';
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-success');
                }
                calculateLiveRevenue();
            });
        }

        // Hlavní výpočet
        function calculateLiveRevenue() {
            let grandTotal = 0;

            cards.forEach(card => {
                const isChecked = card.querySelector('.activity-toggle').checked;
                const model = card.getAttribute('data-model');
                const resultText = card.querySelector('.activity-potential-text');
                
                if (!isChecked) {
                    card.classList.add('disabled-card');
                    resultText.innerText = '0 Kč';
                    return;
                }
                
                card.classList.remove('disabled-card');
                
                // Načtení hodnot z políček
                const kids = parseFloat(card.querySelector('.input-kids').value) || 0;
                const price = parseFloat(card.querySelector('.input-price').value) || 0;
                const days = parseFloat(card.querySelector('.input-days').value) || 0;
                
                let subTotal = 0;

                // Matematika podle modelu
                if (model === 'hourly') {
                    const hoursInput = card.querySelector('.input-hours');
                    const hours = hoursInput ? (parseFloat(hoursInput.value) || 0) : 1;
                    subTotal = kids * hours * price * days;
                } else {
                    // Pro denní a měsíční paušál je to stejné: Děti × Sazba × Frekvence
                    subTotal = kids * price * days;
                }

                resultText.innerText = subTotal.toLocaleString('cs-CZ') + ' Kč';
                grandTotal += subTotal;
            });

            totalDisplay.innerText = grandTotal.toLocaleString('cs-CZ') + ' Kč';
        }

        // Napojení posluchačů změn (na checkboxy i inputy)
        cards.forEach(card => {
            const inputs = card.querySelectorAll('input');
            inputs.forEach(input => {
                // Přepočítá se při psaní i při kliknutí na přepínač
                input.addEventListener('input', calculateLiveRevenue);
                input.addEventListener('change', calculateLiveRevenue);
            });
        });

        // První spuštění při načtení stránky
        calculateLiveRevenue();
    });
</script>
@endsection