@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin – Cestovní deník')

@section('content')
    {{-- Načtení ikon pro administraci (aby fungovaly zmdi ikony) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

    <div class="container py-5">

        {{-- Hlavička --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Cestovní deník</h3>
                <p class="text-muted small mb-0">Správa záznamů z vašich cest</p>
            </div>
            <a href="{{ route('diary.create') }}" class="btn btn-success shadow-sm rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Nový zápis
            </a>
        </div>

        {{-- Karta obalující tabulku --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 15%;">Datum a čas</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-center" style="font-size: 0.85rem; width: 10%;">Ikona</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem; width: 55%;">Obsah zápisu</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-end" style="font-size: 0.85rem; width: 20%;">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                {{-- Datum --}}
                                <td class="px-4 py-3">
                                    <span class="d-block fw-bold text-dark fs-6">{{ $post->created_at->format('d. m. Y') }}</span>
                                    <span class="text-muted small"><i class="fa-regular fa-clock me-1"></i>{{ $post->created_at->format('H:i') }}</span>
                                </td>

                                {{-- Ikona --}}
                                <td class="px-4 py-3 text-center">
                                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle" style="width: 40px; height: 40px;">
                                        <i class="{{ $post->icon_class }} fs-5 text-secondary"></i>
                                    </div>
                                </td>

                                {{-- Náhled obsahu, obrázku a mapy --}}
                                <td class="px-4 py-3">
                                    @php
                                        // Vytáhneme z původního textu URL obrázku (pokud existuje)
                                        $thumbnail = null;
                                        if (preg_match('/\[img\](.*?)\[\/img\]/', $post->content, $matches)) {
                                            $thumbnail = $matches[1];
                                        }
                                    @endphp

                                    <div class="d-flex align-items-center gap-3">
                                        @if($thumbnail)
                                            <div class="flex-shrink-0">
                                                <img src="{{ $thumbnail }}" alt="Náhled" class="rounded object-fit-cover shadow-sm border" style="width: 60px; height: 60px;">
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <p class="mb-1 text-dark" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.95rem;">
                                                {{ strip_tags($post->parsed_content) }}
                                            </p>
                                            @if($post->map_url && $post->map_url !== 'none')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill small fw-normal mt-1">
                                                    <i class="fa-solid fa-map-location-dot me-1"></i> Má polohu
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- Akce --}}
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('diary.edit', $post->id) }}" class="btn btn-sm btn-outline-warning text-dark" title="Upravit zápis">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('diary.destroy', $post->id) }}" method="POST"
                                            onsubmit="return confirm('Opravdu chcete smazat tento zápis v deníku?')">
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
                                        <i class="fa-solid fa-plane-departure display-4 mb-3 opacity-25"></i>
                                        <h5 class="fw-bold text-dark">Deník je zatím prázdný</h5>
                                        <p class="small mb-0">Kliknutím na zelené tlačítko vpravo nahoře vytvoříte svůj první záznam z cest.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Stránkování uvnitř karty dole --}}
            @if($posts->hasPages())
                <div class="card-footer bg-white border-top py-3">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection