@extends($layout ?? 'layouts.default.app')

@section('title', 'Správa layout výjimek')
@section('content')
<div class="container py-4">
    <h2 class="mb-4">Výjimky rozvržení</h2>
    <a href="{{ route('admin.layout-overrides.create') }}" class="btn btn-primary mb-3">
        <i class="bi bi-plus-lg"></i> Přidat výjimku
    </a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>URL vzor</th>
                <th>Layout</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($overrides as $item)
                <tr>
                    <td><code>{{ $item->path_pattern }}</code></td>
                    <td>{{ $item->layout }}</td>
                    <td>
                        <form action="{{ route('admin.layout-overrides.destroy', $item) }}" method="POST" onsubmit="return confirm('Opravdu smazat tuto výjimku?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Smazat</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection