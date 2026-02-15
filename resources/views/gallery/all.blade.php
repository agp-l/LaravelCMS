@extends($layout ?? 'layouts.default.app')

@section('title', 'Galerie – všechny skupiny')

@section('content')
<main>
    <div class="container px-4 py-2" id="gallery-all">
        <h2 class="mb-4">Všechny galerie</h2>

        {{-- Přehled skupin --}}
        <div class="mb-4">
            @foreach ($groups as $grp)
                <a href="{{ route('gallery.group', ['group' => $grp]) }}"
                   class="btn btn-outline-primary me-2 mb-2">
                    {{ ucfirst($grp) }}
                </a>
            @endforeach
        </div>

        {{-- Výpis obrázků napříč skupinami --}}
        <div class="row g-4 py-3 row-cols-1 row-cols-lg-3">
            @foreach ($images as $image)
                @foreach ($image->media as $media)
                    <div class="feature col">
                        <div class="image-hover-wrapper mb-2">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal-{{ $media->id }}">
                                <img src="{{ $media->getUrl('thumb') }}" alt="{{ $image->title }}"
                                     class="img-fluid w-100 object-fit-cover" style="height: 300px;">
                                <div class="overlay">
                                    <i class="bi bi-search"></i>
                                </div>
                            </a>
                        </div>

                        <!-- Modal -->
                        <div class="modal fade" id="imageModal-{{ $media->id }}" tabindex="-1"
                             aria-labelledby="imageModalLabel-{{ $media->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-xl">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-body p-0 text-center">
                                        <img src="{{ $media->getFullUrl() }}" class="img-fluid rounded shadow" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if (!empty($image->title))
                            <h3 class="fs-5">{{ $image->title }}</h3>
                        @endif

                        @if (!empty($image->perex))
                            <p class="fw-light lh-base" style="text-align: justify; text-justify: inter-character;">
                                {{ $image->perex }}
                            </p>
                        @endif
                    </div>
                @endforeach
            @endforeach
        </div>

        {{-- stránkování --}}
        <div class="justify-content-center mt-4">
            {{ $images->links() }}
        </div>
    </div>
</main>
@endsection
