@extends($layout ?? 'layouts.default.app')

@section('content')
@php
    $blocks = json_decode($page->content, true);

    $isNoteJson = json_last_error() === JSON_ERROR_NONE;

    $isValidBlocksJson = $isNoteJson
        && is_array($blocks)
        && isset($blocks[0])
        && is_array($blocks[0])
        && array_key_exists('type', $blocks[0])
        && array_key_exists('columns', $blocks[0])
        && is_array($blocks[0]['columns']);

    // ✅ Blokový režim jen pokud:
    // 1) JSON odpovídá našemu formátu
    // 2) TinyMCE NENÍ vypnutý
    $isJson = $isValidBlocksJson && !session('tinymce_disabled');
@endphp

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Upravit stránku</h1>
        
        @if(request()->has('history_id'))
            <a href="{{ route('page.edit', $page->id) }}" class="btn btn-outline-secondary btn-sm">
                ❌ Zrušit náhled historie
            </a>
        @endif
    </div>

    {{-- Varovná hláška při zobrazení historie (zůstává nahoře přes celou šířku) --}}
    @if(isset($page->is_history_preview) && $page->is_history_preview)
        <div class="alert alert-warning border-warning shadow-sm mb-4" role="alert">
            <strong>⚠️ Pozor!</strong> Máte načtenou starší verzi stránky ze dne <strong>{{ \Carbon\Carbon::parse($page->history_date)->format('d. m. Y H:i:s') }}</strong>.<br>
            Pokud nyní kliknete na tlačítko <strong>Uložit změny</strong>, tato stará verze přepíše současný stav webu. Current verze se před přepsáním samozřejmě opět zazálohuje.
        </div>
    @endif

    <div class="row">
        {{-- =========================
             LEVÝ SLOUPEC: FORMULÁŘ (Širší)
        ========================== --}}
        <div class="col-lg-9 col-md-8">
            <form action="{{ route('page.update', $page->id) }}"
                  id="page-form"
                  method="POST"
                  data-editor-mode="{{ $isJson ? 'blocks' : 'html' }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Název stránky</label>
                    <input type="text"
                           name="title"
                           id="title"
                           class="form-control"
                           value="{{ old('title', $page->title) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug (adresa)</label>
                    <input type="text"
                           name="slug"
                           id="slug"
                           class="form-control"
                           value="{{ old('slug', $page->slug) }}">
                </div>

                {{-- =========================
                     BLOKOVÝ REŽIM
                ========================== --}}
                @if ($isJson)
                    <div id="json-blocks">
                        <label class="form-label">Bloky na stránce:</label>

                        @foreach ($blocks as $index => $block)
                            <div class="card mb-3 p-3 border shadow-sm">
                                <div class="mb-2">
                                    <label class="form-label">Šablona</label>
                                    <select name="json_blocks[{{ $index }}][type]" class="form-select">
                                        <option value="component11"
                                            {{ ($block['type'] ?? '') === 'component11' ? 'selected' : '' }}>
                                            component11
                                        </option>
                                        <option value="component12"
                                            {{ ($block['type'] ?? '') === 'component12' ? 'selected' : '' }}>
                                            component12
                                        </option>
                                    </select>
                                </div>

                                @php
                                    $columns = isset($block['columns']) && is_array($block['columns'])
                                        ? $block['columns']
                                        : [];
                                @endphp

                                @foreach ($columns as $key => $value)
                                    <div class="mb-2">
                                        <label class="form-label">{{ $key }}</label>
                                        <input type="text"
                                               name="json_blocks[{{ $index }}][columns][{{ $key }}]"
                                               class="form-control"
                                               value="{{ $value }}">
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        {{-- Skrytá textarea kam se při submitu zapíše JSON --}}
                        <textarea name="content"
                                  id="json-content"
                                  class="form-control d-none"
                                  rows="6">{{ $page->content }}</textarea>
                    </div>

                {{-- =========================
                     HTML / RUČNÍ REŽIM
                ========================== --}}
                @else
                    <div class="mb-3">
                        <label for="content" class="form-label">
                            Obsah (HTML nebo ruční JSON)
                        </label>
                        <textarea name="content"
                                  id="content"
                                  class="form-control"
                                  rows="12">{{ old('content', $page->content) }}</textarea>
                    </div>
                @endif

                <div class="form-check mb-3">
                    <input class="form-check-input"
                           type="checkbox"
                           name="published"
                           id="published"
                           {{ $page->published ? 'checked' : '' }}>
                    <label class="form-check-label" for="published">
                        Zveřejnit
                    </label>
                </div>

                <button type="submit" class="btn btn-success mb-5">
                    Uložit změny
                </button>
            </form>
        </div>

        {{-- =========================
             PRAVÝ SLOUPEC: HISTORIE (Úzký)
        ========================== --}}
        <div class="col-lg-3 col-md-4">
            @if(isset($histories) && $histories->count() > 0)
                {{-- sticky-top zajistí, že panel zůstane na obrazovce i při scrollování dolů --}}
                <div class="card shadow-sm sticky-top" style="top: 2rem; z-index: 10;">
                    <div class="card-header bg-light py-2">
                        <h6 class="mb-0 text-muted" style="font-size: 0.85rem;">
                            <span class="me-1">🕒</span> Historie úprav
                        </h6>
                    </div>
                    
                    {{-- Omezíme výšku a zapneme scrollování --}}
                    <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                        
                        {{-- Položka: Aktuální verze --}}
                        <a href="{{ route('page.edit', $page->id) }}" 
                           class="list-group-item list-group-item-action py-2 {{ !request('history_id') ? 'active' : '' }}">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <small style="font-size: 0.75rem;">
                                    <span class="me-1">🌟</span> Aktuální verze na webu
                                </small>
                            </div>
                        </a>

                        {{-- Položky: Starší verze --}}
                        @foreach($histories as $h)
                            @php
                                $isActive = request('history_id') == $h->id;
                            @endphp
                            <a href="{{ route('page.edit', $page->id) }}?history_id={{ $h->id }}" 
                               class="list-group-item list-group-item-action py-2 {{ $isActive ? 'list-group-item-warning fw-bold' : '' }}">
                                <div class="d-flex w-100 justify-content-between">
                                    {{-- Datum --}}
                                    <small class="{{ $isActive ? 'text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($h->created_at)->format('d. m. Y') }}
                                    </small>
                                    {{-- Čas --}}
                                    <small class="{{ $isActive ? 'text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($h->created_at)->format('H:i') }}
                                    </small>
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection