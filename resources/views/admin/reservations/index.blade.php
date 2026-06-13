@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin – Přehled rezervací')

@section('content')
    <div class="container py-5">

        {{-- Hlavička sekce --}}
        <div class="mb-4">
            <h3 class="mb-0 fw-bold text-dark">Správa rezervací</h3>
            <p class="text-muted small mb-0">Přehled všech registrovaných účastníků a plateb</p>
        </div>

        {{-- Karta obalující tabulku --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 12%;">Termín</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 15%;">Aktivita</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 20%;">Dítě a Rodič</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 18%;">Vybrané hodiny</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 10%;">Cena</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-center" style="font-size: 0.85rem; width: 12%;">Stav platby</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-end" style="font-size: 0.85rem; width: 13%;">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservations as $res)
                            <tr>
                                {{-- Termín --}}
                                <td class="px-4 py-3">
                                    <span class="d-block fw-bold text-dark fs-6">{{ \Carbon\Carbon::parse($res->date)->format('d. m. Y') }}</span>
                                    <span class="text-muted small">{{ $res->sharing_type }}</span>
                                </td>

                                {{-- Aktivita --}}
                                <td class="px-4 py-3">
                                    @if($res->activity)
                                        <span class="badge rounded-pill px-2 py-1 fw-semibold text-truncate d-inline-block" 
                                              style="background: {{ $res->activity->color_theme }}15; color: {{ $res->activity->color_theme }}; max-width: 150px;">
                                            <i class="{{ $res->activity->icon }} me-1"></i> {{ $res->activity->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">Smazaná aktivita</span>
                                    @endif
                                </td>

                                {{-- Údaje o lidech --}}
                                <td class="px-4 py-3">
                                    <span class="d-block text-dark fw-semibold">{{ $res->child_name }} <small class="text-muted">({{ $res->kids_count }} d.)</small></span>
                                    <span class="d-block text-muted small" style="font-size: 0.8rem;"><i class="fa-regular fa-user me-1"></i>{{ $res->parent_name }}</span>
                                    <span class="text-muted small" style="font-size: 0.8rem; display: block;"><i class="fa-regular fa-envelope me-1"></i>{{ $res->contact }}</span>
                                </td>

                                {{-- Sloty / Hodiny --}}
                                <td class="px-4 py-3">
                                    <span class="small text-dark font-monospace bg-light p-1 rounded d-block text-truncate" style="max-width: 180px;">
                                        {{ is_array($res->slots) ? implode(', ', $res->slots) : implode(', ', json_decode($res->slots, true) ?? []) }}
                                    </span>
                                </td>

                                {{-- Cena --}}
                                <td class="px-4 py-3 font-monospace fw-bold text-dark">
                                    {{ number_format($res->total_price, 0, ',', ' ') }} Kč
                                </td>

                                {{-- Stav Platby --}}
                                <td class="px-4 py-3 text-center">
                                    @if ($res->payment_status === 'paid')
                                        <form action="{{ route('admin.reservations.toggle', $res->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 fw-semibold" title="Kliknutím změnit na Nezaplaceno">
                                                <i class="fa-solid fa-circle-check me-1"></i> Zaplaceno
                                            </button>
                                        </form>
                                    @elseif ($res->payment_status === 'pending')
                                        <form action="{{ route('admin.reservations.toggle', $res->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn p-0 border-0 badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2 fw-semibold" title="Kliknutím změnit na Zaplaceno">
                                                <i class="fa-solid fa-clock me-1"></i> Čeká
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2 fw-semibold">
                                            <i class="fa-solid fa-circle-xmark me-1"></i> Zrušeno
                                        </span>
                                    @endif
                                </td>

                                {{-- Akce --}}
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-regular fa-calendar-xmark display-4 mb-3 opacity-25"></i>
                                        <h5 class="fw-bold text-dark">Žádné rezervace k zobrazení</h5>
                                        <p class="small mb-0">V kalendáři na klientském webu zatím nikdo neprovedl žádnou objednávku.</p>
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