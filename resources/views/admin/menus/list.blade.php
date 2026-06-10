@extends($layout ?? 'layouts.default.app')

@section('title', 'Správa menu')

@section('content')


<div class="container my-5">

    <h1><i class="fad fa-bars"></i> Navigace – přehled bez JS</h1>

    <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">
        <i class="fad fa-plus-square"></i> Nový odkaz
    </a>

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
                @php
                    $rowClass = 'table-level-' . ($menu->level ?? 0);
                @endphp
                <tr class="{{ $rowClass }}">
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
                            <i class="fa fa-check"></i>
                        @else
                            <i class="fa fa-times"></i>
                        @endif
                    </td>
                    <td>{{ $menu->order }}</td>
                    <td>
                        <a href="{{ route('menu.edit', $menu->id) }}" class="btn btn-sm btn-warning text-white"><i class="fa fa-pencil"></i></a>

                        <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Opravdu smazat tento odkaz?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
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