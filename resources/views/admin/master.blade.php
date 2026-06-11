@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container my-5">
    


    {{-- ========================================== --}}
    {{-- RYCHLÉ AKCE (Kompletní rozcestník)         --}}
    {{-- ========================================== --}}
    <div class="row g-3 mb-5">
        
        {{-- Tvorba nového obsahu --}}
        <div class="col-6 col-md-3">
            <a href="{{ route('page.create') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-primary text-white text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-file-circle-plus fs-2 mb-2 opacity-75"></i>
                        <h6 class="fw-bold mb-0">Nová stránka</h6>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="col-6 col-md-3">
            <a href="{{ route('article.create') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-success text-white text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-pen-nib fs-2 mb-2 opacity-75"></i>
                        <h6 class="fw-bold mb-0">Nový článek</h6>
                    </div>
                </div>
            </a>
        </div>

        {{-- Přehledy obsahu --}}
        <div class="col-6 col-md-3">
            <a href="{{ route('page.index') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-info text-dark text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-file-lines fs-2 mb-2 opacity-75"></i>
                        <h6 class="fw-bold mb-0">Všechny stránky</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ route('article.index') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 border border-success border-2 bg-white text-dark text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-newspaper fs-2 mb-2 text-success opacity-75"></i>
                        <h6 class="fw-bold mb-0">Všechny články</h6>
                    </div>
                </div>
            </a>
        </div>

        {{-- Správa systému a vzhledu --}}
        <div class="col-6 col-md-3">
            <a href="{{ route('menu.index') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-secondary text-white text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-bars fs-2 mb-2 opacity-75"></i>
                        <h6 class="fw-bold mb-0">Správa menu</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ route('images.index') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-danger text-white text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-regular fa-images fs-2 mb-2 opacity-75"></i>
                        <h6 class="fw-bold mb-0">Galerie / Obrázky</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ route('admin.layout-overrides.index') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm rounded-4 bg-dark text-white text-center hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-palette fs-2 mb-2 opacity-75 text-warning"></i>
                        <h6 class="fw-bold mb-0">Vzhled webu</h6>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-6 col-md-3">
            <a href="{{ route('profile.show') ?? '#' }}" class="text-decoration-none">
                <div class="card h-100 shadow-sm rounded-4 bg-light text-dark text-center border hover-lift">
                    <div class="card-body py-3">
                        <i class="fa-solid fa-user-gear fs-2 mb-2 text-secondary opacity-75"></i>
                        <h6 class="fw-bold mb-0">Můj profil</h6>
                    </div>
                </div>
            </a>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- INFORMAČNÍ PANELY (Rozpracované položky)   --}}
    {{-- ========================================== --}}
    <div class="row g-4">
        
        {{-- PANEL 1: Nezveřejněné stránky --}}
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-warning border-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted mb-0"><i class="fa-regular fa-file-lines me-2"></i>Rozpracované stránky</h6>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="list-group list-group-flush">
                        @forelse($unpublishedPages ?? [] as $page)
                            <a href="{{ route('page.edit', $page->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-truncate pe-2">{{ $page->title }}</span>
                                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                            </a>
                        @empty
                            <div class="text-muted small italic">Všechny stránky jsou zveřejněné.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL 2: Nezveřejněné články --}}
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-success border-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted mb-0"><i class="fa-solid fa-newspaper me-2"></i>Nezveřejněné články</h6>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="list-group list-group-flush">
                        @forelse($unpublishedArticles ?? [] as $article)
                            <a href="{{ route('article.edit', $article->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2">
                                <span class="text-truncate pe-2">{{ $article->title }}</span>
                                <i class="fa-solid fa-chevron-right text-muted" style="font-size: 0.8rem;"></i>
                            </a>
                        @empty
                            <div class="text-muted small italic">Žádné rozpracované články.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- PANEL 3: Skryté odkazy v menu (Připravované projekty) --}}
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 h-100 border-start border-info border-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                    <h6 class="fw-bold text-uppercase text-muted mb-0"><i class="fa-solid fa-link-slash me-2"></i>Skryté menu / Projekty</h6>
                </div>
                <div class="card-body p-4 pt-3">
                    <div class="list-group list-group-flush">
                        @forelse($hiddenMenuItems ?? [] as $item)
                            <a href="{{ route('menu.index') }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center px-0 py-2">
                                {{-- Nyní se zkusí načíst title, label, name, text a pokud nic nevyjde, vypíše se náhradní text --}}
                                <span class="text-truncate pe-2">
                                    {{ $item->title ?? $item->label ?? $item->name ?? $item->text ?? 'Skrytá položka' }}
                                </span>
                                <span class="badge bg-info text-dark" style="font-size: 0.7rem;">Čeká</span>
                            </a>
                        @empty
                            <div class="text-muted small italic">Všechny položky menu jsou viditelné.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Efekt zvednutí pro horní tlačítka */
    .hover-lift {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .hover-lift:hover {
        transform: translateY(-4px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection