@extends($layout ?? 'layouts.default.app')

@section('title', 'Galerie – všechny skupiny')

@section('content')
<main class="py-5 bg-light">
    <div class="container px-4" id="gallery-all">
        
        <div class="text-center mb-4 mx-auto">
            <h2 class="display-6 fw-bold text-dark mb-3">Všechny galerie</h2>
        </div>

        <div class="d-flex flex-wrap justify-content-center gap-2 mb-5">
            @foreach ($groups as $grp)
                <a href="{{ route('gallery.group', ['group' => $grp]) }}"
                   class="btn btn-outline-info rounded-pill px-4 fw-medium shadow-sm">
                    {{ ucfirst($grp) }}
                </a>
            @endforeach
        </div>

        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
            @foreach ($images as $image)
                @foreach ($image->media as $media)
                    <div class="col">
                        <div class="card h-100 border-0 bg-white rounded-4 shadow-sm overflow-hidden">
                            
                            <div class="position-relative bg-light" style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $media->id }}">
                                
                                <img src="{{ $media->getUrl('thumb') }}" alt="{{ $image->title ?? 'Obrázek galerie' }}" class="img-fluid w-100" style="height: 300px; object-fit: cover;">
                                
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50 opacity-0 hover-overlay transition">
                                    <i class="fa-solid fa-magnifying-glass text-white fs-1"></i>
                                </div>
                            </div>

                            <div class="modal fade" id="imageModal-{{ $media->id }}" tabindex="-1" aria-labelledby="imageModalLabel-{{ $media->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content bg-transparent border-0">
                                        <div class="modal-header border-0 position-absolute top-0 end-0 z-3 p-3">
                                            <button type="button" class="btn-close btn-close-white fs-4 shadow-none" data-bs-dismiss="modal" aria-label="Zavřít"></button>
                                        </div>
                                        
                                        <div class="modal-body p-0 text-center">
                                            <img src="{{ $media->getFullUrl() }}" class="img-fluid rounded-4 shadow-lg" alt="{{ $image->title ?? 'Obrázek v plné velikosti' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if (!empty($image->title) || !empty($image->perex))
                                <div class="card-body p-4">
                                    @if (!empty($image->title))
                                        <h3 class="h5 fw-bold text-dark mb-2">{{ $image->title }}</h3>
                                    @endif

                                    @if (!empty($image->perex))
                                        <p class="card-text fw-light text-muted lh-base mb-0">
                                            {{ $image->perex }}
                                        </p>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-5 pt-3">
            {{ $images->links() }}
        </div>
        
    </div>
</main>

<style>
    .hover-overlay {
        transition: opacity 0.3s ease-in-out;
    }
    .position-relative:hover .hover-overlay {
        opacity: 1 !important;
    }
</style>
@endsection