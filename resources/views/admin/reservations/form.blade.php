@extends($layout ?? 'layouts.default.app')

@section('title', 'Upravit rezervaci')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <h3 class="mb-0 fw-bold text-dark">Úprava rezervace #{{ $reservation->id }}</h3>
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

                    <form action="{{ route('admin.reservations.update', $reservation->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4"><i class="fa-regular fa-calendar-days me-2 text-primary"></i>Termín a Aktivita</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="date" class="form-label fw-bold text-muted small text-uppercase">Datum rezervace</label>
                                <input type="date" class="form-control bg-light border-0 py-2" id="date" name="date" 
                                    value="{{ old('date', \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label for="activity_id" class="form-label fw-bold text-muted small text-uppercase">Zvolená aktivita</label>
                                <select class="form-select bg-light border-0 py-2" id="activity_id" name="activity_id" required>
                                    @foreach($activities as $act)
                                        <option value="{{ $act->id }}" {{ $reservation->activity_id == $act->id ? 'selected' : '' }}>{{ $act->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12">
                                <label for="slots" class="form-label fw-bold text-muted small text-uppercase">Rezervované hodiny (oddělené čárkou)</label>
                                <input type="text" class="form-control bg-light border-0 py-2 font-monospace" id="slots" name="slots" 
                                    value="{{ old('slots', is_array($reservation->slots) ? implode(', ', $reservation->slots) : implode(', ', json_decode($reservation->slots, true) ?? [])) }}" required>
                                <div class="form-text small">Zapisujte přesně ve formátu: <code>09:30 - 10:30, 10:30 - 11:30</code></div>
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i class="fa-regular fa-user me-2 text-primary"></i>Účastníci a Kontakt</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-8">
                                <label for="child_name" class="form-label fw-bold text-muted small text-uppercase">Jméno dítěte / dětí</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="child_name" name="child_name" value="{{ old('child_name', $reservation->child_name) }}" required>
                            </div>
                            <div class="col-md-4">
                                <label for="kids_count" class="form-label fw-bold text-muted small text-uppercase">Počet dětí celkem</label>
                                <input type="number" class="form-control bg-light border-0 py-2" id="kids_count" name="kids_count" value="{{ old('kids_count', $reservation->kids_count) }}" min="1" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="parent_name" class="form-label fw-bold text-muted small text-uppercase">Jméno rodiče</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="parent_name" name="parent_name" value="{{ old('parent_name', $reservation->parent_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="contact" class="form-label fw-bold text-muted small text-uppercase">Kontakt (E-mail / Telefon)</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="contact" name="contact" value="{{ old('contact', $reservation->contact) }}" required>
                            </div>
                        </div>

                        <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i class="fa-solid fa-credit-card me-2 text-primary"></i>Typ a Stav platby</h5>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="sharing_type" class="form-label fw-bold text-muted small text-uppercase">Sdílení skupiny</label>
                                <select class="form-select bg-light border-0 py-2" id="sharing_type" name="sharing_type" required>
                                    <option value="Sdílený čas" {{ $reservation->sharing_type === 'Sdílený čas' ? 'selected' : '' }}>Otevřená skupina</option>
                                    <option value="Individuální čas" {{ $reservation->sharing_type === 'Individuální čas' ? 'selected' : '' }}>Soukromě</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="pricing_model" class="form-label fw-bold text-muted small text-uppercase">Cenový model</label>
                                <select class="form-select bg-light border-0 py-2" id="pricing_model" name="pricing_model" required>
                                    <option value="Parťák na hodinu" {{ $reservation->pricing_model === 'Parťák na hodinu' ? 'selected' : '' }}>Parťák na hodinu</option>
                                    <option value="Celodenní parťák" {{ $reservation->pricing_model === 'Celodenní parťák' ? 'selected' : '' }}>Celodenní parťák</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label for="total_price" class="form-label fw-bold text-muted small text-uppercase">Vypočítaná cena (Kč)</label>
                                <input type="number" class="form-control bg-light border-0 py-2 font-monospace fw-bold text-primary" id="total_price" name="total_price" value="{{ old('total_price', $reservation->total_price) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="payment_status" class="form-label fw-bold text-muted small text-uppercase">Stav platby</label>
                                <select class="form-select bg-light border-0 py-2 fw-semibold" id="payment_status" name="payment_status" required>
                                    <option value="pending" class="text-warning" {{ $reservation->payment_status === 'pending' ? 'selected' : '' }}>⏱️ Čeká na platbu</option>
                                    <option value="paid" class="text-success" {{ $reservation->payment_status === 'paid' ? 'selected' : '' }}>✅ Zaplaceno</option>
                                    <option value="cancelled" class="text-danger" {{ $reservation->payment_status === 'cancelled' ? 'selected' : '' }}>❌ Zrušeno</option>
                                </select>
                            </div>
                        </div>

                        <div class="text-end border-top pt-4">
                            <button type="submit" class="btn btn-success btn-lg shadow-sm rounded-pill px-5 fw-bold">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Uložit změny v rezervaci
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection