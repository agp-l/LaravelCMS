@extends($layout ?? 'layouts.default.app')

@section('title', 'Správa menu')

@section('content')

<div class="container my-5">
    <h1><i class="fad fa-bars"></i> Navigace – řazení</h1>

    <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">
        <i class="fad fa-plus-square"></i> Nový odkaz
    </a>
    <a href="{{ route('menu.list') }}" class="btn btn-warning mb-3">
        <i class="fad fa-bars"></i> List menu
    </a>

    <form id="menu-order-form" method="POST" action="{{ route('menu.reorder') }}">
        @csrf
        <input type="hidden" name="order_data" id="order-data">

        <div id="nested-menu">
            @php
                $menus = collect($menus);

                function renderMenuLevel($items, $parentId = null, $level = 0) {
                    $children = $items->filter(fn($item) => $item->parent_id === $parentId);

                    if ($children->isEmpty()) return;

                    echo '<div class="list-group nested-sortable ms-' . ($level * 2) . '">';

                    foreach ($children as $menu) {
                        echo '<div class="list-group-item" data-id="' . $menu->id . '">';
                        echo '<div class="d-flex flex-column flex-md-row justify-content-between align-items-start">';
                        echo '<div class="w-100">
                                <strong>' . str_repeat('— ', $level) . $menu->label . '</strong><br>
                                <small class="text-muted">Úroveň: ' . $level . ' | URL: ' . $menu->url . ' | Typ: ' . $menu->type . ' | Zveřejněno: ' . ($menu->published ? '✔️' : '❌') . ' | Pořadí: ' . $menu->order . '</small>
                            </div>';
                        echo '<div class="mt-2 mt-md-0 ms-md-3 d-flex">';

                        echo '<a href="' . route('menu.edit', $menu->id) . '" class="btn btn-sm btn-warning text-white me-1"><i class="fa fa-pencil"></i></a>';

                        echo '<div class="d-inline" data-form="' . route('menu.destroy', $menu->id) . '">';
                        echo '<button class="btn btn-sm btn-danger" onclick="return confirmDelete(this)"><i class="fa fa-trash"></i></button>';
                        echo '</div>';

                        echo '</div>';
                        echo '</div>';
                        renderMenuLevel($items, $menu->id, $level + 1);
                        echo '</div>';
                    }

                    echo '</div>';
                }
            @endphp

            {!! renderMenuLevel($menus) !!}
        </div>

        <button type="submit" id="submit-button" class="btn btn-primary mt-3">
            <i class="fa fa-save"></i> Uložit nové pořadí
        </button>
    </form>
</div>

@endsection
