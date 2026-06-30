@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin – Přehled rezervací')

@section('content')
    <div class="container py-5">

        {{-- Hlavička sekce --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-4 gap-3">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Správa rezervací</h3>
                <p class="text-muted small mb-0">Přehled všech registrovaných účastníků a plateb</p>
            </div>
            
            {{-- Filtrování a řazení --}}
            <form action="{{ route('admin.reservations.index') }}" method="GET" class="d-flex flex-column flex-sm-row gap-2 w-100" style="max-width: 500px;">
                
                {{-- Filtr: Typ aktivity --}}
                <select name="activity_id" class="form-select border-0 shadow-sm flex-fill" onchange="this.form.submit()">
                    <option value="">Všechny aktivity</option>
                    @foreach($activities as $act)
                        <option value="{{ $act->id }}" {{ $activityFilter == $act->id ? 'selected' : '' }}>
                            {{ $act->name }}
                        </option>
                    @endforeach
                </select>

                {{-- Filtr: Řazení --}}
                <select name="sort_by" class="form-select border-0 shadow-sm flex-fill" onchange="this.form.submit()">
                    <option value="date_desc" {{ $sortBy == 'date_desc' ? 'selected' : '' }}>Termín (Od nejnovějších)</option>
                    <option value="date_asc" {{ $sortBy == 'date_asc' ? 'selected' : '' }}>Termín (Od nejbližších)</option>
                    <option value="created_desc" {{ $sortBy == 'created_desc' ? 'selected' : '' }}>Zadáno (Nejnovější)</option>
                </select>

                <noscript><button type="submit" class="btn btn-primary">Filtrovat</button></noscript>
            </form>
        </div>

        {{-- Karta obalující tabulku --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 30%;">Detaily rezervace</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 40%;">Účastníci a Kontakt</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-center" style="font-size: 0.85rem; width: 15%;">Platba</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-end" style="font-size: 0.85rem; width: 15%;">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $res)
                            <tr>
                                {{-- SLOUP 1: Detaily rezervace (Aktivita, Termín, Hodiny) --}}
                                <td class="px-4 py-3 align-top">
                                    <div class="mb-2">
                                        @if($res->activity)
                                            <span class="badge rounded-pill px-2 py-1 fw-semibold text-wrap d-inline-block text-start lh-base" 
                                                  style="background: {{ $res->activity->color_theme }}15; color: {{ $res->activity->color_theme }};">
                                                <i class="{{ $res->activity->icon }} me-1"></i> {{ $res->activity->name }}
                                            </span>
                                        @else
                                            <span class="text-muted small"><i class="fa-solid fa-ghost me-1"></i>Smazaná aktivita</span>
                                        @endif
                                    </div>

                                    <div class="d-flex align-items-center mb-1">
                                        <i class="fa-regular fa-calendar text-muted me-2"></i>
                                        <span class="fw-bold text-dark fs-6">
                                            @if($res->date_end)
                                                Od {{ \Carbon\Carbon::parse($res->date)->format('d. m.') }} do {{ \Carbon\Carbon::parse($res->date_end)->format('d. m. Y') }}
                                            @else
                                                {{ \Carbon\Carbon::parse($res->date)->format('d. m. Y') }}
                                            @endif
                                        </span>
                                    </div>

                                    @if($res->date_end && $res->recurring_days)
                                        <div class="text-muted small mb-2">
                                            <i class="fa-solid fa-rotate-right me-1"></i> Pravidelně: 
                                            @php
                                                $daysMap = [1 => 'Po', 2 => 'Út', 3 => 'St', 4 => 'Čt', 5 => 'Pá', 6 => 'So', 0 => 'Ne'];
                                                $recDays = is_array($res->recurring_days) ? $res->recurring_days : json_decode($res->recurring_days, true);
                                                if (is_array($recDays)) {
                                                    $daysNames = array_map(function($d) use ($daysMap) { return $daysMap[$d] ?? $d; }, $recDays);
                                                    echo implode(', ', $daysNames);
                                                }
                                            @endphp
                                        </div>
                                    @endif
                                    
                                    <div class="d-flex flex-wrap gap-1 mb-2 mt-2">
                                        @php
                                            $slotsArray = is_array($res->slots) ? $res->slots : json_decode($res->slots, true) ?? [];
                                        @endphp
                                        @foreach($slotsArray as $slotItem)
                                            <span class="small text-dark font-monospace bg-light px-2 py-1 rounded border" style="font-size: 0.75rem;">
                                                <i class="fa-regular fa-clock text-muted me-1"></i>{{ $slotItem }}
                                            </span>
                                        @endforeach
                                    </div>

                                    <div class="text-muted small d-block mb-1">
                                        <strong>Typ:</strong> {{ $res->sharing_type }}
                                    </div>

                                    <div class="text-muted" style="font-size: 0.7rem;" title="Datum přijetí rezervace">
                                        <i class="fa-solid fa-download me-1"></i> Přijato: {{ $res->created_at->format('d.m.Y H:i') }}
                                    </div>
                                </td>

                                {{-- SLOUP 2: Účastníci a Kontakt (Lidé, Poznámka, Věk) --}}
                                <td class="px-4 py-3 align-top">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <span class="d-block text-dark fw-bold fs-6">{{ $res->child_name }}</span>
                                        <span class="badge bg-secondary rounded-pill fw-normal" style="font-size: 0.75rem;">{{ $res->kids_count }} d.</span>
                                    </div>
                                    
                                    @if(!empty($res->child_info))
                                        <span class="d-block text-muted small fst-italic mb-2" style="font-size: 0.8rem;">
                                            Věk: {{ $res->child_info }}
                                        </span>
                                    @endif

                                    @if(!empty($res->custom_field_value))
                                        <span class="d-block text-primary small fw-bold mb-2" style="font-size: 0.8rem;">
                                            Vlastní údaj: <span class="fw-normal text-dark">{{ $res->custom_field_value }}</span>
                                        </span>
                                    @endif

                                    <div class="border-top pt-2 mt-2">
                                        <span class="d-block text-dark small" style="font-size: 0.85rem;"><i class="fa-regular fa-user text-muted me-2"></i>{{ $res->parent_name }}</span>
                                        <span class="d-block text-dark small mt-1" style="font-size: 0.85rem;"><i class="fa-regular fa-envelope text-muted me-2"></i>{{ $res->contact }}</span>
                                    </div>

                                    @if(!empty($res->note))
                                        <div class="mt-3 p-2 rounded-3 shadow-sm" style="background-color: #fffbeb; border-left: 3px solid #f59e0b; color: #92400e; font-size: 0.8rem; line-height: 1.4;">
                                            <strong><i class="fa-regular fa-comment-dots me-1"></i>Poznámka:</strong><br>
                                            {{ $res->note }}
                                        </div>
                                    @endif
                                </td>

                                {{-- SLOUP 3: Cena a Stav Platby --}}
                                <td class="px-4 py-3 text-center align-top">
                                    <div class="font-monospace fw-bold text-dark mb-2 fs-6">
                                        {{ number_format($res->total_price, 0, ',', ' ') }} Kč
                                    </div>

                                    @if ($res->payment_status === 'paid')
                                        <form action="{{ route('admin.reservations.toggle', $res->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 fw-semibold w-100" title="Kliknutím změnit na Nezaplaceno">
                                                <i class="fa-solid fa-circle-check me-1"></i> Zaplaceno
                                            </button>
                                        </form>
                                    @elseif ($res->payment_status === 'pending')
                                        <form action="{{ route('admin.reservations.toggle', $res->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2 fw-semibold w-100" title="Kliknutím změnit na Zaplaceno">
                                                <i class="fa-solid fa-clock me-1"></i> Čeká
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2 fw-semibold w-100">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Zrušeno
                                        </span>
                                    @endif
                                </td>

                               
                                {{-- SLOUP 4: Akce --}}
                                <td class="px-4 py-3 text-end align-top">
                                    <div class="d-flex justify-content-end gap-2">
                                        
                                        {{-- TLAČÍTKA PRO KALENDÁŘ --}}
                                        <a href="{{ route('admin.reservations.google', $res->id) }}" target="_blank" class="btn btn-sm btn-outline-primary" title="Přidat do Google Kalendáře">
                                            <i class="fa-brands fa-google"></i>
                                        </a>
                                        
                                        <a href="{{ route('admin.reservations.ics', $res->id) }}" class="btn btn-sm btn-outline-info" title="Stáhnout .ics pro Apple/Proton/Outlook">
                                            <i class="fa-regular fa-calendar-plus"></i>
                                        </a>
                                        {{-- KONEC TLAČÍTEK PRO KALENDÁŘ --}}

                                        <a href="{{ route('admin.reservations.edit', $res->id) }}" class="btn btn-sm btn-outline-warning text-dark" title="Upravit data rezervace">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('admin.reservations.destroy', $res->id) }}" method="POST"
                                            onsubmit="return confirm('Opravdu chcete tuto rezervaci bez náhrady smazat?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Smazat">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-regular fa-calendar-xmark display-4 mb-3 opacity-25"></i>
                                        <h5 class="fw-bold text-dark">Žádné rezervace k zobrazení</h5>
                                        <p class="small mb-0">Zkuste změnit filtr, nebo zatím nikdo neprovedl rezervaci.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($reservations->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection