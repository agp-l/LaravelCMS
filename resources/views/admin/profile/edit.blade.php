@extends($layout ?? 'layouts.default.app')


@section('title', 'Admin – Úprava profilu')

@section('content')
    <div class="container my-5 mt-5">
        <h3 class="mb-4">Úprava profilu</h3>

        @if(isset($user) && is_object($user))
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                {{-- Jméno --}}
                <div class="mb-3">
                    <label for="name" class="form-label">Jméno</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name ?? '') }}"
                        required>
                    @error('name') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Email --}}
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', $user->email ?? '') }}" required>
                    @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Heslo (volitelné) --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Nové heslo <small class="text-muted">(nevyplňuj, pokud nechceš
                            měnit)</small></label>
                    <input type="password" name="password" id="password" class="form-control">
                    @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                {{-- Potvrzení hesla --}}
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Potvrzení hesla</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Uložit změny</button>
                <a href="{{ route('profile.show') }}" class="btn btn-secondary">Zpět</a>
            </form>
        @else
            <div class="alert alert-danger">
                Uživatelská data nejsou dostupná.
            </div>
        @endif
    </div>
@endsection