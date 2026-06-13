@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin – Aktivity a rezervace')

@section('content')
    <div class="container py-5">

        {{-- Hlavička --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Aktivity a rezervace</h3>
                <p class="text-muted small mb-0">Správa nabízených kurzů, výletů a jejich cen</p>
            </div>
            <a href="{{ route('admin.activities.create') }}" class="btn btn-success shadow-sm rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Nová aktivita
            </a>
        </div>

        {{-- Karta obalující tabulku --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 8%;">Ikona</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 35%;">Název a popisek</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 20%;">Cena / Hodina</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 22%;">Štítek rozvrhu</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-end" style="font-size: 0.85rem; width: 15%;">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $activity)
                            <tr>
                                {{-- Ikona v barvě tématu --}}
                                <td class="px-4 py-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center rounded-circle" 
                                         style="width: 45px; height: 45px; background-color: {{ $activity->color_theme }}15;">
                                        <i class="{{ $activity->icon }} fs-5" style="color: {{ $activity->color_theme }};"></i>
                                    </div>
                                </td>

                                {{-- Název a popis --}}
                                <td class="px-4 py-3">
                                    <span class="d-block fw-bold text-dark fs-6">{{ $activity->name }}</span>
                                    <span class="text-muted small" style="display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $activity->description }}
                                    </span>
                                </td>

                                {{-- Cena --}}
                                <td class="px-4 py-3">
                                    @if ($activity->price_per_hour > 0)
                                        <span class="fw-bold text-dark">{{ number_format($activity->price_per_hour, 0, ',', ' ') }} Kč</span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1 fw-semibold">
                                            Zdarma / Dobrovolně
                                        </span>
                                    @endif
                                </td>

                                {{-- Štítek --}}
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border px-2 py-1 fw-normal">
                                        <i class="fa-regular fa-clock me-1 text-muted"></i> {{ $activity->schedule_tag }}
                                    </span>
                                </td>

                                {{-- Akce --}}
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('admin.activities.edit', $activity->id) }}" class="btn btn-sm btn-outline-warning text-dark" title="Upravit aktivitu">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('admin.activities.destroy', $activity->id) }}" method="POST"
                                            onsubmit="return confirm('Opravdu chcete smazat tuto aktivitu? Smažou se tím i její rozvrhy!')">
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
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-solid fa-puzzle-piece display-4 mb-3 opacity-25"></i>
                                        <h5 class="fw-bold text-dark">Zatím žádné aktivity</h5>
                                        <p class="small mb-0">Kliknutím na tlačítko Nová aktivita založíte první položku pro rezervace.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection