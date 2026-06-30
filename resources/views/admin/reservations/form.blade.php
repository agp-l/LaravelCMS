@extends($layout ?? 'layouts.default.app')

@section('title', 'Upravit rezervaci')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="d-flex align-items-center mb-4">
                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-outline-secondary rounded-circle me-3"
                        style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
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

                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-4"><i
                                    class="fa-regular fa-calendar-days me-2 text-primary"></i>Termín a Aktivita</h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-4">
                                    <label for="date" class="form-label fw-bold text-muted small text-uppercase">Datum
                                        začátku</label>
                                    <input type="date" class="form-control bg-light border-0 py-2" id="date"
                                        name="date"
                                        value="{{ old('date', \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')) }}"
                                        required>
                                </div>

                                <div class="col-md-4">
                                    <label for="date_end" class="form-label fw-bold text-muted small text-uppercase">Datum
                                        konce (Paušál)</label>
                                    <input type="date" class="form-control bg-light border-0 py-2" id="date_end"
                                        name="date_end"
                                        value="{{ old('date_end', $reservation->date_end ? \Carbon\Carbon::parse($reservation->date_end)->format('Y-m-d') : '') }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="activity_id"
                                        class="form-label fw-bold text-muted small text-uppercase">Zvolená aktivita</label>
                                    <select class="form-select bg-light border-0 py-2" id="activity_id" name="activity_id"
                                        required>
                                        @foreach ($activities as $act)
                                            <option value="{{ $act->id }}"
                                                {{ $reservation->activity_id == $act->id ? 'selected' : '' }}>
                                                {{ $act->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mb-4">
                                @php
                                    $savedDays = old(
                                        'recurring_days',
                                        is_array($reservation->recurring_days)
                                            ? $reservation->recurring_days
                                            : json_decode($reservation->recurring_days, true) ?? [],
                                    );
                                @endphp
                                <label class="form-label fw-bold text-muted small text-uppercase">Opakující se dny (Pouze
                                    pro paušál)</label>
                                <div class="d-flex flex-wrap gap-3 p-3 bg-light rounded-3">
                                    @foreach ([1 => 'Pondělí', 2 => 'Úterý', 3 => 'Středa', 4 => 'Čtvrtek', 5 => 'Pátek', 6 => 'Sobota', 0 => 'Neděle'] as $val => $label)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="recurring_days[]"
                                                value="{{ $val }}" id="rec_day_{{ $val }}"
                                                {{ is_array($savedDays) && in_array($val, $savedDays) ? 'checked' : '' }}>
                                            <label class="form-check-label"
                                                for="rec_day_{{ $val }}">{{ $label }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                           <div class="mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Rezervované hodiny</label>
                                <div class="p-3 bg-light rounded-3 border">
                                    <div class="row g-2">
                                        @php
                                            // Převedeme uložené sloty na pole
                                            $savedSlots = is_array($reservation->slots) ? $reservation->slots : (json_decode($reservation->slots, true) ?? []);
                                            $allPossibleSlots = [];

                                            // Pokud máme aktivitu, dynamicky z ní vytáhneme její hodiny
                                            if ($reservation->activity) {
                                                // Zjistíme, jaký den v týdnu je rezervace (0 = Ne, 1 = Po...)
                                                $dayOfWeek = \Carbon\Carbon::parse($reservation->date)->dayOfWeek;
                                                
                                                // Vytáhneme pravidla rozvrhu pro tento konkrétní den
                                                $rules = $reservation->activity->scheduleRules->where('day_of_week', $dayOfWeek)->whereNull('date_override');
                                                
                                                // Pokud zrovna tento den nemá pravidla, vezmeme všechna pravidla aktivity (jako zálohu)
                                                if ($rules->isEmpty()) {
                                                    $rules = $reservation->activity->scheduleRules;
                                                }

                                                // Z pravidel vygenerujeme hodinové bloky
                                                foreach ($rules as $rule) {
                                                    $startHour = intval(substr($rule->start_time, 0, 2));
                                                    $endHour = intval(substr($rule->end_time, 0, 2));

                                                    for ($h = $startHour; $h < $endHour; $h++) {
                                                        $slot = sprintf('%02d:00 - %02d:00', $h, $h + 1);
                                                        if (!in_array($slot, $allPossibleSlots)) {
                                                            $allPossibleSlots[] = $slot;
                                                        }
                                                    }
                                                }
                                            }

                                            // POJISTKA: Přidáme i ty už uložené časy.
                                            // Důvod: Pokud bys mezitím v administraci změnil rozvrh aktivity (např. zrušil odpoledne), 
                                            // stará rezervace by tu hodinu ztratila. Takhle se tam ukáže a ty ji můžeš odškrtnout.
                                            foreach ($savedSlots as $saved) {
                                                if (!in_array($saved, $allPossibleSlots)) {
                                                    $allPossibleSlots[] = $saved;
                                                }
                                            }

                                            // Seřadíme časy hezky od rána do večera
                                            sort($allPossibleSlots);
                                        @endphp

                                        @forelse($allPossibleSlots as $slot)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="slots[]" value="{{ $slot }}" id="slot_{{ $loop->index }}" 
                                                        {{ in_array($slot, $savedSlots) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="slot_{{ $loop->index }}">
                                                        {{ $slot }}
                                                    </label>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="col-12 text-muted small">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i> Pro tuto aktivitu a datum nebyly nalezeny žádné časové bloky.
                                            </div>
                                        @endforelse
                                    </div>
                                    @error('slots')
                                        <div class="text-danger small fw-bold mt-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Musíte vybrat alespoň jednu hodinu!</div>
                                    @enderror
                                </div>
                            </div>

                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i
                                    class="fa-regular fa-user me-2 text-primary"></i>Účastníci a Kontakt</h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-8">
                                    <label for="child_name" class="form-label fw-bold text-muted small text-uppercase">Jméno
                                        dítěte / dětí</label>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="child_name"
                                        name="child_name" value="{{ old('child_name', $reservation->child_name) }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="kids_count" class="form-label fw-bold text-muted small text-uppercase">Počet
                                        dětí celkem</label>
                                    <input type="number" class="form-control bg-light border-0 py-2" id="kids_count"
                                        name="kids_count" value="{{ old('kids_count', $reservation->kids_count) }}"
                                        min="1">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="child_info" class="form-label fw-bold text-muted small text-uppercase">Věk dětí
                                    (Doplňující info)</label>
                                <input type="text" class="form-control bg-light border-0 py-2" id="child_info"
                                    name="child_info" value="{{ old('child_info', $reservation->child_info) }}">
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="parent_name"
                                        class="form-label fw-bold text-muted small text-uppercase">Jméno rodiče /
                                        Zákazníka</label>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="parent_name"
                                        name="parent_name" value="{{ old('parent_name', $reservation->parent_name) }}"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact"
                                        class="form-label fw-bold text-muted small text-uppercase">Kontakt
                                        (E-mail / Telefon)</label>
                                    <input type="text" class="form-control bg-light border-0 py-2" id="contact"
                                        name="contact" value="{{ old('contact', $reservation->contact) }}" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="note"
                                    class="form-label fw-bold text-muted small text-uppercase">Poznámka</label>
                                <textarea class="form-control bg-light border-0 py-2" id="note" name="note" rows="3">{{ old('note', $reservation->note) }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="custom_field_value"
                                    class="form-label fw-bold text-muted small text-uppercase">Odpověď z vlastního
                                    políčka</label>
                                <textarea class="form-control bg-light border-0 py-2" id="custom_field_value" name="custom_field_value"
                                    rows="2">{{ old('custom_field_value', $reservation->custom_field_value) }}</textarea>
                            </div>


                            <h5 class="fw-bold text-dark border-bottom pb-2 mb-4 mt-5"><i
                                    class="fa-solid fa-credit-card me-2 text-primary"></i>Typ a Stav platby</h5>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="sharing_type"
                                        class="form-label fw-bold text-muted small text-uppercase">Sdílení skupiny</label>
                                    <select class="form-select bg-light border-0 py-2" id="sharing_type"
                                        name="sharing_type" required>
                                        <option value="Sdílený čas"
                                            {{ $reservation->sharing_type === 'Sdílený čas' ? 'selected' : '' }}>Otevřená
                                            skupina</option>
                                        <option value="Individuální čas"
                                            {{ $reservation->sharing_type === 'Individuální čas' ? 'selected' : '' }}>
                                            Soukromě</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="pricing_model"
                                        class="form-label fw-bold text-muted small text-uppercase">Cenový model</label>
                                    <select class="form-select bg-light border-0 py-2" id="pricing_model"
                                        name="pricing_model" required>
                                        <option value="hourly"
                                            {{ $reservation->pricing_model === 'hourly' ? 'selected' : '' }}>Od hodiny
                                        </option>
                                        <option value="daily"
                                            {{ $reservation->pricing_model === 'daily' ? 'selected' : '' }}>Denní paušál
                                        </option>
                                        <option value="monthly"
                                            {{ $reservation->pricing_model === 'monthly' ? 'selected' : '' }}>Měsíční
                                            paušál</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-3 mb-5">
                                <div class="col-md-6">
                                    <label for="total_price"
                                        class="form-label fw-bold text-muted small text-uppercase">Vypočítaná cena
                                        (Kč)</label>
                                    <input type="number"
                                        class="form-control bg-light border-0 py-2 font-monospace fw-bold text-primary"
                                        id="total_price" name="total_price"
                                        value="{{ old('total_price', $reservation->total_price) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="payment_status"
                                        class="form-label fw-bold text-muted small text-uppercase">Stav platby</label>
                                    <select class="form-select bg-light border-0 py-2 fw-semibold" id="payment_status"
                                        name="payment_status" required>
                                        <option value="pending" class="text-warning"
                                            {{ $reservation->payment_status === 'pending' ? 'selected' : '' }}>⏱️ Čeká na
                                            platbu</option>
                                        <option value="paid" class="text-success"
                                            {{ $reservation->payment_status === 'paid' ? 'selected' : '' }}>✅ Zaplaceno
                                        </option>
                                        <option value="cancelled" class="text-danger"
                                            {{ $reservation->payment_status === 'cancelled' ? 'selected' : '' }}>❌ Zrušeno
                                        </option>
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
