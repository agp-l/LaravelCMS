@extends($layout ?? 'layouts.default.app')

@section('title', 'Upravit stránku')

{{-- ================================================================= --}}
{{-- 1. CHYTRÁ HLAVIČKA (Zabraňuje uskakování layoutu nahoru)          --}}
{{-- ================================================================= --}}
@section('header')
    @php
        $headerData = null;
        if (preg_match('/\[hlavicka\s+(.*?)\]/s', $page->content ?? '', $matches)) {
            preg_match_all('/(\w+)="([^"]*)"/s', $matches[1], $attrMatches);
            $headerData = [];
            foreach ($attrMatches[1] as $index => $key) {
                $headerData[$key] = $attrMatches[2][$index];
            }
        }
        $themeFolder = str_contains($layout ?? 'default', 'mizzle') ? 'mizzle' : 'default';
    @endphp

    @if($headerData)
        @include($themeFolder . '.headers.' . ($headerData['typ'] ?? 'hero'), $headerData)
    @else
        @include($themeFolder . '.carousel')
    @endif
@endsection

{{-- ================================================================= --}}
{{-- 2. HLAVNÍ OBSAH (Formulář a historie)                             --}}
{{-- ================================================================= --}}
@section('content')
<section class="page" style="position: relative; z-index: 10; background-color: #f8f9fa;">

    {{-- Identifikační proužek administrace --}}
    <div class="bg-dark text-white py-3 border-bottom border-warning border-3 shadow-sm mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <span class="fw-bold"><i class="fa-solid fa-user-gear text-warning me-2"></i>Administrace webu</span>
            <span class="badge bg-warning text-dark fw-bold">Režim úprav stránky</span>
        </div>
    </div>

    <div class="container pb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Upravit stránku</h1>
            
            @if(request()->has('history_id'))
                <a href="{{ route('page.edit', $page->id) }}" class="btn btn-outline-secondary btn-sm">
                    ❌ Zrušit náhled historie
                </a>
            @endif
        </div>

        @if(isset($page->is_history_preview) && $page->is_history_preview)
            <div class="alert alert-warning border-warning shadow-sm mb-4" role="alert">
                <strong>⚠️ Pozor!</strong> Máte načtenou starší verzi stránky ze dne <strong>{{ \Carbon\Carbon::parse($page->history_date)->format('d. m. Y H:i:s') }}</strong>.<br>
                Pokud nyní kliknete na Uložit, stará verze přepíše současný stav webu.
            </div>
        @endif

        <div class="row">
            {{-- LEVÝ SLOUPEC: FORMULÁŘ --}}
            <div class="col-lg-9 col-md-8">
                <form action="{{ route('page.update', $page->id) }}" id="page-form" method="POST">
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

                    <div class="mb-3">
                        <label for="content" class="form-label">Obsah (HTML a Shortcodes)</label>
                        <textarea name="content" id="content" class="form-control" rows="15">{{ old('content', $page->content) }}</textarea>
                    </div>

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="published" id="published" {{ $page->published ? 'checked' : '' }}>
                        <label class="form-check-label" for="published">Zveřejnit</label>
                    </div>

                    <button type="submit" class="btn btn-success mb-5">Uložit změny</button>
                </form>
            </div>

            {{-- PRAVÝ SLOUPEC: HISTORIE --}}
            <div class="col-lg-3 col-md-4">
                @if(isset($histories) && $histories->count() > 0)
                    <div class="card shadow-sm sticky-top" style="top: 2rem; z-index: 10;">
                        <div class="card-header bg-light py-2">
                            <h6 class="mb-0 text-muted" style="font-size: 0.85rem;"><span class="me-1">🕒</span> Historie úprav</h6>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                            <a href="{{ route('page.edit', $page->id) }}" class="list-group-item list-group-item-action py-2 {{ !request('history_id') ? 'active' : '' }}">
                                <div class="d-flex w-100 justify-content-between align-items-center">
                                    <small style="font-size: 0.75rem;"><span class="me-1">🌟</span> Aktuální verze na webu</small>
                                </div>
                            </a>
                            @foreach($histories as $h)
                                @php $isActive = request('history_id') == $h->id; @endphp
                                <a href="{{ route('page.edit', $page->id) }}?history_id={{ $h->id }}" class="list-group-item list-group-item-action py-2 {{ $isActive ? 'list-group-item-warning fw-bold' : '' }}">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="{{ $isActive ? 'text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($h->created_at)->format('d. m. Y') }}</small>
                                        <small class="{{ $isActive ? 'text-dark' : 'text-muted' }}" style="font-size: 0.75rem;">{{ \Carbon\Carbon::parse($h->created_at)->format('H:i') }}</small>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ================================================================= --}}
{{-- 3. JAVASCRIPT PRO PŘEPÍNÁNÍ EDITORŮ (TinyMCE vs CodeMirror)       --}}
{{-- ================================================================= --}}
@php
    // Čteme novou proměnnou (tinymce_enabled), kterou jsme změnili ve web.php
    $isTinyMceEnabled = session('tinymce_enabled') === true;
@endphp

@if($isTinyMceEnabled)
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var textArea = document.getElementById("content");
            if (textArea) {
                tinymce.init({
                    selector: '#content',
                    height: 600,
                    language: 'cs',
                    plugins: 'lists link image code table',
                    toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
                    setup: function (editor) {
                        editor.on('change', function () {
                            editor.save(); 
                        });
                    }
                });
            }
        });
    </script>
@else
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/monokai.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var textArea = document.getElementById("content");
            var form = document.getElementById("page-form");

            if (textArea) {
                var editor = CodeMirror.fromTextArea(textArea, {
                    mode: "htmlmixed",
                    theme: "monokai",
                    lineNumbers: true,
                    matchBrackets: true,
                    lineWrapping: true,
                    indentUnit: 4
                });
                editor.setSize(null, "600px");

                if (form) {
                    form.addEventListener("submit", function() {
                        editor.save(); 
                    });
                }
            }
        });
    </script>
@endif

@endsection