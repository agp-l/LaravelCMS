@extends($layout ?? 'layouts.default.app')

@section('title', 'Správa menu')

@section('content')

<div class="container my-5">
    <h1><i class="fa-solid fa-bars"></i> Navigace – řazení s JS</h1>

    <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">
        <i class="fa-solid fa-plus-square"></i> Nový odkaz
    </a>
    <a href="{{ route('menu.list') }}" class="btn btn-warning mb-3">
        <i class="fa-solid fa-bars"></i> List menu
    </a>

    <form id="menu-order-form" method="POST" action="{{ route('menu.reorder') }}" style="display: none;">
        @csrf
        <input type="hidden" name="order_data" id="order-data">
    </form>

    <div id="nested-menu">
        @php
            $menus = collect($menus);

            function renderMenuLevel($items, $parentId = null, $level = 0) {
                $children = $items->filter(fn($item) => $item->parent_id === $parentId);

                if ($children->isEmpty()) return;

                echo '<div class="list-group nested-sortable ms-' . ($level * 2) . '" data-parent="' . ($parentId ?? 'null') . '">';

                foreach ($children as $menu) {
                    echo '<div class="list-group-item shadow-sm mb-2 rounded border" data-id="' . $menu->id . '">';
                    echo '<div class="d-flex flex-column flex-md-row justify-content-between align-items-start">';
                    
                    // Změněna ikonka na fa-solid (aby ladila s novým FontAwesome)
                    echo '<div class="w-100 cursor-move">
                            <i class="fa-solid fa-arrows-up-down-left-right text-muted me-2"></i><strong>' . str_repeat('— ', $level) . $menu->label . '</strong><br>
                            <small class="text-muted">Úroveň: ' . $level . ' | URL: ' . $menu->url . ' | Typ: ' . $menu->type . ' | Zveřejněno: ' . ($menu->published ? '✔️' : '❌') . ' | Pořadí: ' . $menu->order . '</small>
                        </div>';
                    echo '<div class="mt-2 mt-md-0 ms-md-3 d-flex align-items-center">';

                    echo '<a href="' . route('menu.edit', $menu->id) . '" class="btn btn-sm btn-warning text-white me-2"><i class="fa-solid fa-pencil"></i></a>';

                    // Tento formulář pro smazání už NEKOLIDUJE s hlavním formulářem, protože ten je teď jinde
                    echo '<form action="' . route('menu.destroy', $menu->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Opravdu smazat tento odkaz?\')">';
                    echo '<input type="hidden" name="_token" value="' . csrf_token() . '">';
                    echo '<input type="hidden" name="_method" value="DELETE">';
                    echo '<button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>';
                    echo '</form>';

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

    <button type="button" id="submit-button" class="btn btn-primary mt-3">
        <i class="fa-solid fa-save"></i> Uložit nové pořadí
    </button>
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable'));

    for (var i = 0; i < nestedSortables.length; i++) {
        new Sortable(nestedSortables[i], {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            handle: '.cursor-move',
            onEnd: function() {
                serializeMenu();
            }
        });
    }

    function serializeMenu() {
        var serialized = [];
        var items = document.querySelectorAll('#nested-menu .list-group-item');
        
        items.forEach(function(item, index) {
            var id = item.getAttribute('data-id');
            var parentGroup = item.closest('.nested-sortable');
            var parentId = parentGroup ? parentGroup.getAttribute('data-parent') : null;
            
            serialized.push({
                id: id,
                order: index + 1,
                parent_id: parentId === 'null' ? null : parentId
            });
        });

        document.getElementById('order-data').value = JSON.stringify(serialized);
    }

    // Naplníme data rovnou při načtení (pro jistotu)
    serializeMenu();

    // 3. ZMĚNA: Kliknutí na tlačítko naplní data a odešle skrytý formulář
    document.getElementById('submit-button').addEventListener('click', function() {
        serializeMenu();
        document.getElementById('menu-order-form').submit();
    });
});
</script>

<style>
.cursor-move { cursor: move; }
</style>

@endsection