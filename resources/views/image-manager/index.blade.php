@extends($layout ?? 'layouts.default.app')

@section('title', 'Správce obrázků')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Správce obrázků</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Formulář pro nahrání obrázku --}}
        <div class="border border-primary rounded-1 p-4 mb-4 bg-light-subtle shadow-sm">
            <form action="{{ route('images.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="image" class="form-label">Obrázek</label>
                        <input type="file" name="image" id="image" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label for="collection" class="form-label">Typ obrázku</label>
                        <select name="collection" id="collection" class="form-select" required>
                            <option value="gallery">Galerie</option>
                            <option value="pages">Stránky</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="title" class="form-label">Název obrázku</label>
                        <input type="text" name="title" id="title" class="form-control" placeholder="Např. Na výletě">
                    </div>

                    <div class="col-md-6">
                        <label for="group" class="form-label">Skupina galerie</label>
                        <input type="text" name="group" id="group" class="form-control"
                            placeholder="např. vylety, skola, 2024" required>
                    </div>

                    <div class="col-md-12">
                        <label for="perex" class="form-label">Popis / perex (volitelné)</label>
                        <textarea name="perex" id="perex" class="form-control" rows="2"
                            placeholder="Např. výlet do Beskyd 2024..."></textarea>
                    </div>

                    <div class="col-12 text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-cloud-arrow-up me-1"></i> Nahrát obrázek
                        </button>
                    </div>
                </div>
            </form>
        </div>


        {{-- Filtrování podle kolekce --}}
        <div class="mb-3">
            <form method="GET" action="{{ route('images.index') }}" class="d-inline-block">
                <select name="collection" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                    <option value="" {{ $collection == '' ? 'selected' : '' }}>Všechny kolekce</option>
                    <option value="gallery" {{ $collection == 'gallery' ? 'selected' : '' }}>Galerie</option>
                    <option value="pages" {{ $collection == 'pages' ? 'selected' : '' }}>Stránky</option>
                </select>
            </form>


            @if (!empty($collection) && $groups->count())
                <form method="GET" action="{{ route('images.index') }}" class="d-inline-block ms-3">
                    <input type="hidden" name="collection" value="{{ $collection }}">
                    @if(request()->has('sort'))
                        <input type="hidden" name="sort" value="{{ $sort }}">
                    @endif
                    <select name="group" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                        <option value="">Všechny skupiny</option>
                        @foreach ($groups as $grp)
                            <option value="{{ $grp }}" {{ $group == $grp ? 'selected' : '' }}>
                                {{ ucfirst($grp) }}
                            </option>
                        @endforeach

                    </select>
                </form>
            @endif
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            @foreach ($images as $image)
                @foreach ($image->media as $media)
                    @php
                        $relativeUrl = parse_url($media->getFullUrl(), PHP_URL_PATH);
                    @endphp
        
                    <div class="col">
                        <div class="card h-100 border-0 shadow rounded-1">
                            <div class="image-hover-wrapper position-relative">
                                <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $media->id }}">
                                    <img src="{{ $media->getUrl('thumb') ?? $media->getFullUrl() }}"
                                        alt="{{ $image->title }}"
                                        class="card-img-top img-fluid rounded-1" style="border-radius: 0.1rem;">
                                    <div class="overlay">
                                        <i class="bi bi-search"></i>
                                    </div>
                                </a>
                            </div>
        
                            <div class="card-body">
                                @if (!empty($image->perex))
                                    <p class="fw-light lh-base small text-muted mb-2" style="text-align: justify;">
                                        {{ $image->perex }}
                                    </p>
                                @endif
        
                                @if ($image->title)
                                    <p class="text-primary-emphasis mb-1">
                                        <strong>{{ $image->title }}</strong>
                                    </p>
                                @endif
        
                                @if ($image->group)
                                    <p class="small text-muted mb-1">
                                        <i class="bi bi-folder"></i> Skupina: {{ ucfirst($image->group) }}
                                    </p>
                                @endif
        
                                <p class="small text-muted mb-3">
                                    <i class="bi bi-tags"></i> Kolekce: {{ $media->collection_name == 'gallery' ? 'Galerie' : 'Stránky' }}
                                </p>
        
                                <div class="mb-3">
                                    <label class="form-label text-muted small">Adresa obrázku:</label>
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control" value="{{ $relativeUrl }}" readonly
                                            id="img-path-{{ $media->id }}">
                                        <button class="btn btn-outline-secondary" type="button"
                                            onclick="copyToClipboard('img-path-{{ $media->id }}')">
                                            <i class="bi bi-clipboard"></i>
                                        </button>
                                    </div>
                                </div>
        
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="{{ $media->getFullUrl() }}" target="_blank" class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> Zobrazit
                                    </a>
        
                                    <a href="{{ $media->getUrl('thumb') ?? $media->getFullUrl() }}" target="_blank"
                                        class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-image"></i> Miniatura
                                    </a>
        
                                    <form action="{{ route('images.destroy', $image->id) }}" method="POST"
                                        onsubmit="return confirm('Opravdu smazat obrázek?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i> Smazat
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
        
                        {{-- Modal pro náhled --}}
                        <div class="modal fade" id="imageModal-{{ $media->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body p-0 text-center">
                                        <img src="{{ $media->getFullUrl() }}" class="img-fluid rounded shadow" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        
        <div class="d-flex justify-content-center mt-4">
            {{ $images->appends(['collection' => $collection, 'group' => $group ?? null])->links() }}
        </div>
    </div>

@endsection