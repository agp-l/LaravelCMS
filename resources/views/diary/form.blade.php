@extends($layout ?? 'layouts.default.app')

@section('title', $post->exists ? 'Upravit zápis v deníku' : 'Nový zápis do deníku')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('diary.admin') }}" class="btn btn-outline-secondary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="mb-0 fw-bold text-dark">{{ $post->exists ? 'Úprava záznamu' : 'Nový záznam z cest' }}</h3>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3 mb-4">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ $post->exists ? route('diary.update', $post->id) : route('diary.store') }}" method="POST">
                        @csrf
                        @if($post->exists)
                            @method('PUT')
                        @endif

                        <div class="row g-4 mb-4">
                            {{-- Datum a čas --}}
                            <div class="col-md-6">
                                <label for="created_at" class="form-label fw-bold text-muted small text-uppercase">Datum a čas</label>
                                <input type="datetime-local" class="form-control bg-light border-0 py-2" id="created_at" name="created_at" 
                                    value="{{ old('created_at', $post->created_at ? $post->created_at->format('Y-m-d\TH:i') : now()->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            {{-- CSS Třída Ikony --}}
                            <div class="col-md-6">
                                <label for="icon_class" class="form-label fw-bold text-muted small text-uppercase">Třída ikony (zMDi)</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="icon_class" name="icon_class" 
                                    value="{{ old('icon_class', $post->icon_class ?? 'zmdi zmdi-local-see') }}" required>
                                <div class="form-text small">Např: <code>zmdi zmdi-airplane</code>, <code>zmdi zmdi-local-see</code></div>
                            </div>
                        </div>

                        {{-- URL Mapy --}}
                        <div class="mb-4">
                            <label for="map_url" class="form-label fw-bold text-muted small text-uppercase">Odkaz na mapu (volitelné)</label>
                            <input type="text" class="form-control bg-light border-0 py-2" id="map_url" name="map_url" 
                                value="{{ old('map_url', $post->map_url === 'none' ? '' : $post->map_url) }}" placeholder="https://google.com/maps/...">
                        </div>

                        {{-- Samotný obsah - OPRAVENÉ ID PROTI KONFLIKTŮM --}}
                        <div class="mb-5">
                            <label for="diary_content_textarea" class="form-label fw-bold text-muted small text-uppercase">Obsah (Podporuje [img], [b], [p])</label>
                            <textarea class="form-control bg-light border-0 font-monospace" id="diary_content_textarea" name="content" rows="12" required>{{ old('content', $post->content) }}</textarea>
                        </div>

                        {{-- Tlačítko uložení --}}
                        <div class="text-end">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm rounded-pill px-5 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> {{ $post->exists ? 'Uložit změny' : 'Zapsat do deníku' }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection