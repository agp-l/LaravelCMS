@extends('layouts.app')

@section('title', 'Admin – všechny stránky')

@section('content')
    <div class="container">

        <h3 class="mb-4">Články</h3>

        <a href="{{ route('article.create') }}" class="btn btn-success mb-3">+ Přidat nový článek</a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Stav</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($articles as $article)
                    <tr>
                        <td>{{ $article->title }}</td>
                        <td>
                            @if ($article->published)
                                <span class="badge bg-success">Zveřejněno</span>
                            @else
                                <span class="badge bg-secondary">Nezveřejněno</span>
                            @endif
                        </td>
                        <td>
                            @if ($article->slug)
                                <a href="{{ route('article.show', ['slug' => $article->slug]) }}"
                                    class="btn btn-sm btn-primary">Zobrazit</a>
                            @else
                                <span class="text-muted">Bez slugu</span>
                            @endif

                            <a href="{{ route('article.edit', $article->id) }}" class="btn btn-sm btn-warning">Upravit</a>
                            <form action="{{ route('article.toggle', $article->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary">
                                    {{ $article->published ? 'Skrýt' : 'Zveřejnit' }}
                                </button>
                            </form>

                            <form action="{{ route('article.destroy', $article->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Smazat stránku?')"
                                    class="btn btn-sm btn-danger">Smazat</button>
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