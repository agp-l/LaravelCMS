@extends('layouts.app')

@section('title', 'Správa menu')

@section('content')
    <div class="container">

        <h1>🧭 Navigace – přehled</h1>

        <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">➕ Nový odkaz</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Úroveň</th>
                    <th>URL</th>
                    <th>Typ</th>
                    <th>Zveřejněno</th>
                    <th>Pořadí</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menus as $menu)
                    <tr>
                        <td>
                            {{ $menu->label }}
                        </td>

                        <td style="padding-left: {{ $menu->level * 20 }}px;">
                            {!! str_repeat('— ', $menu->level) !!}{{ $menu->label }} ({{ $menu->level ?? 'není definováno' }})
                        </td>

                        <td>{{ $menu->url }}</td>
                        <td>{{ $menu->type }}</td>
                        <td>
                            @if ($menu->published)
                                ✅
                            @else
                                ❌
                            @endif
                        </td>
                        <td>{{ $menu->order }}</td>
                        <td>
                            <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-warning">✏️ Upravit</a>

                            <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Opravdu smazat tento odkaz?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6">Žádné položky menu zatím nejsou.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection