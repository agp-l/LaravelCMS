<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalace CMS - Krok 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-success">Skvělé! Databáze je připravena.</h2>
                    <p class="text-muted">Nyní vytvoříme hlavní účet administrátora.</p>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">👤 Účet správce</h5>
                    </div>
                    <div class="card-body p-4">
                        
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('install.admin.process') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Jméno a příjmení</label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">E-mail (Přihlašovací jméno)</label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Heslo (min. 8 znaků)</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-bold">Potvrzení hesla</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">
                                Dokončit instalaci ✔️
                            </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>