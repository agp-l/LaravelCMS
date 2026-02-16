@extends($layout ?? 'layouts.default.app')

@section('content')
@php
    $blocks = json_decode($page->content, true);

    // Přísná detekce: zapneme blokový režim jen pokud content odpovídá
    // našemu formátu: array bloků s klíči type + columns.
    $isNoteJson = json_last_error() === JSON_ERROR_NONE;
    $isJson = $isNoteJson
        && is_array($blocks)
        && isset($blocks[0])
        && is_array($blocks[0])
        && array_key_exists('type', $blocks[0])
        && array_key_exists('columns', $blocks[0])
        && is_array($blocks[0]['columns']);
@endphp

<div class="container my-5">
    <h1>Upravit stránku</h1>

    <form action="{{ route('page.update', $page->id) }}"
          id="page-form"
          method="POST"
          data-editor-mode="{{ $isJson ? 'blocks' : 'html' }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Název stránky</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $page->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="slug" class="form-label">Slug (adresa)</label>
            <input type="text" name="slug" id="slug" class="form-control" value="{{ old('slug', $page->slug) }}">
        </div>

        @if ($isJson)
            <div id="json-blocks">
                <label class="form-label">Bloky na stránce:</label>

                @foreach ($blocks as $index => $block)
                    <div class="card mb-3 p-3 border shadow-sm">
                        <div class="mb-2">
                            <label class="form-label">Šablona</label>
                            <select name="json_blocks[{{ $index }}][type]" class="form-select">
                                <option value="component11" {{ ($block['type'] ?? '') === 'component11' ? 'selected' : '' }}>component11</option>
                                <option value="component12" {{ ($block['type'] ?? '') === 'component12' ? 'selected' : '' }}>component12</option>
                                <!-- Přidej další šablony zde -->
                            </select>
                        </div>

                        @php
                            $columns = isset($block['columns']) && is_array($block['columns']) ? $block['columns'] : [];
                        @endphp

                        @foreach ($columns as $key => $value)
                            <div class="mb-2">
                                <label class="form-label">{{ $key }}</label>
                                <input
                                    type="text"
                                    name="json_blocks[{{ $index }}][columns][{{ $key }}]"
                                    class="form-control"
                                    value="{{ $value }}"
                                >
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <!-- Sem se při submitu zapíše vygenerovaný JSON -->
                <textarea name="content" id="json-content" class="form-control d-none" rows="6">{{ $page->content }}</textarea>
            </div>
        @else
            <div class="mb-3">
                <label for="content" class="form-label">Obsah (HTML nebo text)</label>
                <textarea name="content" id="content" class="form-control" rows="10">{{ old('content', $page->content) }}</textarea>
            </div>
        @endif

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="published" id="published" {{ $page->published ? 'checked' : '' }}>
            <label class="form-check-label" for="published">Zveřejnit</label>
        </div>

        <button type="submit" class="btn btn-success">Uložit změny</button>
    </form>
</div>
@endsection