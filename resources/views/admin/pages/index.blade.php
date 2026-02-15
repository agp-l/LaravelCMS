@extends($layout ?? 'layouts.default.app')

@section('title', 'Admin – statické stránky')

@section('content')
    <div class="container my-5">

        <h3 class="mb-4">Stránky</h3>

        <a href="{{ route('page.create') }}" class="btn btn-success mb-3">+ Přidat novou stránku</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Stav</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>
                            @if ($page->published)
                                <span class="badge bg-success">Zveřejněno</span>
                            @else
                                <span class="badge bg-secondary">Nezveřejněno</span>
                            @endif
                        </td>
                        <td>
                            @if ($page->slug)
                                <a href="{{ \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), route('page.show', ['slug' => $page->slug], false)) }}"
                                    class="btn btn-sm btn-primary" target="_blank">
                                    Zobrazit
                                </a>
                            @else
                                <span class="text-muted">Bez slugu</span>
                            @endif

                            <a href="{{ route('page.edit', $page->id) }}" class="btn btn-sm btn-warning">Upravit</a>

                            <form action="{{ route('page.toggle', $page->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary">
                                    {{ $page->published ? 'Skrýt' : 'Zveřejnit' }}
                                </button>
                            </form>

                            <form action="{{ route('page.destroy', $page->id) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('Opravdu smazat tuto stránku?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Smazat</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">Žádné stránky zatím neexistují.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection