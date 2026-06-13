@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin – všechny stránky')

@section('content')
    <div class="container py-5">

        {{-- Hlavička s flexboxem (Nápis vlevo, tlačítko vpravo) --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="mb-0 fw-bold text-dark">Správa článků</h3>
                <p class="text-muted small mb-0">Přehled a úprava článků na blogu</p>
            </div>
            <a href="{{ route('article.create') }}" class="btn btn-success shadow-sm rounded-pill px-4 fw-bold">
                <i class="fa-solid fa-plus me-2"></i> Nový článek
            </a>
        </div>

        {{-- Filtr kategorií (Moderní vzhled s ikonkou) --}}
        <div class="mb-4" style="max-width: 350px;">
            <form method="GET" action="{{ route('article.index') }}">
                <div class="input-group shadow-sm rounded-3 overflow-hidden border">
                    <span class="input-group-text bg-light border-0"><i class="fa-solid fa-filter text-muted"></i></span>
                    <select name="category" onchange="this.form.submit()" class="form-select border-0 bg-light fw-medium" style="box-shadow: none; cursor: pointer;">
                        <option value="">-- Všechny kategorie --</option>
                        @foreach(\App\Models\Article::select('category')->distinct()->pluck('category') as $cat)
                            @if ($cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        {{-- Karta obalující tabulku --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold" style="font-size: 0.85rem;">Název a adresa</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-center" style="font-size: 0.85rem;">Stav</th>
                            <th class="py-3 px-4 text-uppercase text-muted fw-bold text-end" style="font-size: 0.85rem;">Akce</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($articles as $article)
                            <tr>
                                {{-- Sloupec: Název a Slug --}}
                                <td class="px-4 py-3">
                                    <span class="d-block fw-bold text-dark fs-6">{{ $article->title }}</span>
                                    @if ($article->slug)
                                        <span class="text-muted small"><i class="fa-solid fa-link me-1 opacity-50"></i>/{{ $article->slug }}</span>
                                    @else
                                        <span class="text-muted small text-warning"><i class="fa-solid fa-triangle-exclamation me-1"></i>Bez slugu</span>
                                    @endif
                                </td>

                                {{-- Sloupec: Stav (Moderní pill badges) --}}
                                <td class="px-4 py-3 text-center">
                                    @if ($article->published)
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 fw-semibold">
                                            <i class="fa-solid fa-circle-check me-1"></i> Zveřejněno
                                        </span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3 py-2 fw-semibold">
                                            <i class="fa-solid fa-eye-slash me-1"></i> Nezveřejněno
                                        </span>
                                    @endif
                                </td>

                                {{-- Sloupec: Akce (Flexbox s mezerami a ikonami) --}}
                                <td class="px-4 py-3 text-end">
                                    <div class="d-flex justify-content-end gap-2">
                                        
                                        @if ($article->slug)
                                            <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('article.show', ['slug' => $article->slug], false)) }}"
                                                class="btn btn-sm btn-outline-primary" target="_blank" title="Zobrazit na webu">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @endif

                                        <a href="{{ route('article.edit', $article->id) }}" class="btn btn-sm btn-outline-warning text-dark" title="Upravit článek">
                                            <i class="fa-solid fa-pen-to-square me-1"></i> Upravit
                                        </a>

                                        <form action="{{ route('article.toggle', $article->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-outline-secondary" title="{{ $article->published ? 'Skrýt článek' : 'Zveřejnit článek' }}">
                                                <i class="fa-solid {{ $article->published ? 'fa-eye-slash' : 'fa-eye' }} me-1"></i>
                                                {{ $article->published ? 'Skrýt' : 'Zveřejnit' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('article.destroy', $article->id) }}" method="POST"
                                            onsubmit="return confirm('Opravdu chcete smazat tento článek? Tuto akci nelze vzít zpět.')">
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
                            {{-- Moderní zobrazení, když je tabulka prázdná (např. po vyfiltrování neexistující kategorie) --}}
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fa-regular fa-newspaper display-4 mb-3 opacity-25"></i>
                                        <h5 class="fw-bold text-dark">Žádné články k zobrazení</h5>
                                        <p class="small mb-0">Zatím neexistují žádné články, nebo se pro zvolenou kategorii nic nenašlo.</p>
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