@extends($layout ?? 'layouts.default.app')

@section('content')
<div class="container my-5">
    <h1>Upravit stránku</h1>

    <form action="{{ route('page.update', $page->id) }}"  id="page-form" method="POST">
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

        @php
            $blocks = json_decode($page->content, true);
            $isJson = is_array($blocks);
        @endphp

        @if ($isJson)
            <div id="json-blocks">
                <label class="form-label">Bloky na stránce:</label>

                @foreach ($blocks as $index => $block)
                    <div class="card mb-3 p-3 border shadow-sm">
                        <div class="mb-2">
                            <label class="form-label">Šablona</label>
                            <select name="json_blocks[{{ $index }}][type]" class="form-select">
                                <option value="component11" {{ $block['type'] === 'component11' ? 'selected' : '' }}>component11</option>
                                <option value="component12" {{ $block['type'] === 'component12' ? 'selected' : '' }}>component12</option>
                                <!-- Přidej další šablony zde -->
                            </select>
                        </div>

                        @foreach ($block['columns'] as $key => $value)
                            <div class="mb-2">
                                <label class="form-label">{{ $key }}</label>
                                <input type="text" name="json_blocks[{{ $index }}][columns][{{ $key }}]" class="form-control" value="{{ $value }}">
                            </div>
                        @endforeach
                    </div>
                @endforeach

                <!-- Starý obsah pro JSON musí být zkopírován do textarea -->
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
