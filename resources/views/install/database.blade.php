<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instalace CMS - Krok 1</title>
    {{-- Načtení Bootstrapu pro hezký vzhled i bez zkompilovaného CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">

                {{-- Logo nebo nadpis --}}
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-primary">Můj CMS</h2>
                    <p class="text-muted">Prvotní nastavení systému</p>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">🔌 Připojení k databázi</h5>
                    </div>
                    <div class="card-body p-4">

                        {{-- Zobrazení chyb (např. když se nepodaří připojit) --}}
                        @if ($errors->any())
                            <div class="alert alert-danger shadow-sm">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('install.database.process') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="db_host" class="form-label fw-bold">Databázový server (Host)</label>
                                <input type="text" name="db_host" id="db_host" class="form-control"
                                    value="{{ old('db_host', '127.0.0.1') }}" required>
                                <div class="form-text">Většinou bývá <code>127.0.0.1</code> nebo <code>localhost</code>.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="db_port" class="form-label fw-bold">Port</label>
                                <input type="text" name="db_port" id="db_port" class="form-control"
                                    value="{{ old('db_port', '3306') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="db_database" class="form-label fw-bold">Název databáze</label>
                                <input type="text" name="db_database" id="db_database" class="form-control"
                                    value="{{ old('db_database') }}" placeholder="např. muj_web" required>
                            </div>

                            <div class="mb-3">
                                <label for="db_username" class="form-label fw-bold">Uživatelské jméno</label>
                                <input type="text" name="db_username" id="db_username" class="form-control"
                                    value="{{ old('db_username') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="db_prefix" class="form-label fw-bold">Prefix tabulek</label>
                                <input type="text" name="db_prefix" id="db_prefix" class="form-control"
                                    value="{{ old('db_prefix') }}" placeholder="např. test_">
                                <div class="form-text">Pokud sdílíte databázi s jiným webem, zadejte prefix (např.
                                    <code>test_</code>). Jinak nechte prázdné.</div>
                            </div>

                            <div class="mb-4">
                                <label for="db_password" class="form-label fw-bold">Heslo k databázi</label>

                                <div class="mb-4">
                                    <label for="db_password" class="form-label fw-bold">Heslo k databázi</label>
                                    <input type="password" name="db_password" id="db_password" class="form-control">
                                    <div class="form-text">Pokud používáte lokální server bez hesla, nechte prázdné.
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">
                                    Otestovat připojení a pokračovat ➔
                                </button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>

</html>
