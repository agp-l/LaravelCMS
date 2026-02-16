<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<!-- Theme JS -->
<script src="/template/mizzle/js/bootstrap.bundle.min.js"></script>
<script src="/template/mizzle/js/purecounter_vanilla.js"></script>
<script src="/template/mizzle/js/index.js"></script>
<script src="/template/mizzle/js/swiper-bundle.min.js"></script>
<script src="/template/mizzle/js/functions.js"></script>

<!-- TinyMCE -->
<script src="https://cdn.tiny.cloud/1/dcpt068f6z0iexoyf3nng4ck3m92hgfr53phm4opmcqv405v/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>

<script>
console.log('MARKER: mizzle/scripts.blade.php loaded');
</script>

{{-- -------------------------------------------------- --}}
{{-- SLUGIFY --}}
{{-- -------------------------------------------------- --}}
<script>
function slugify(text) {
    return text
        .toString()
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-')
        .replace(/-+/g, '-');
}

document.addEventListener('DOMContentLoaded', function () {
    const titleInput = document.getElementById('title');
    const slugInput  = document.getElementById('slug');

    if (!titleInput || !slugInput) return;

    titleInput.addEventListener('input', function () {
        if (!slugInput.value || slugInput.value === slugify(slugInput.value)) {
            slugInput.value = slugify(titleInput.value);
        }
    });
});
</script>

{{-- -------------------------------------------------- --}}
{{-- COPY TO CLIPBOARD --}}
{{-- -------------------------------------------------- --}}
<script>
function copyToClipboard(id) {
    const input = document.getElementById(id);
    if (!input) return;

    navigator.clipboard.writeText(input.value)
        .then(() => alert('Adresa zkopírována: ' + input.value))
        .catch(() => alert('Chyba při kopírování'));
}
</script>

{{-- -------------------------------------------------- --}}
{{-- TINYMCE --}}
{{-- -------------------------------------------------- --}}
@if (!session('tinymce_disabled'))
<script>
tinymce.init({
    selector: 'textarea#content',
    plugins: 'lists link image code',
    toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
    menubar: false,
    height: 400,
    entity_encoding: 'raw',

    verify_html: false,
    valid_elements: '*[*]',
    extended_valid_elements: '*[*]',
    valid_children: '+body[*]',
    forced_root_block: false,

    relative_urls: false,
    remove_script_host: false,
    convert_urls: false,
});
</script>
@endif

{{-- -------------------------------------------------- --}}
{{-- SORTABLE MENU --}}
{{-- -------------------------------------------------- --}}
@if (!session('tinymce_disabled'))
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const form   = document.getElementById('menu-order-form');
    const input  = document.getElementById('order-data');
    const groups = document.querySelectorAll('.nested-sortable');

    if (!form || !input || !groups.length) return;

    groups.forEach(el => {
        new Sortable(el, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65
        });
    });

    form.addEventListener('submit', function () {

        const order = [];

        function traverse(listGroup, parentId = null) {
            const items = Array.from(listGroup.children)
                .filter(el => el.classList.contains('list-group-item'));

            items.forEach((item, index) => {
                const id = item.getAttribute('data-id');
                if (id) {
                    order.push({ id, parent_id: parentId, order: index });
                }

                const nested = item.querySelector('.nested-sortable');
                if (nested) traverse(nested, id);
            });
        }

        document.querySelectorAll('#nested-menu > .nested-sortable')
            .forEach(group => traverse(group));

        input.value = JSON.stringify(order);
    });

});
</script>
@endif

{{-- -------------------------------------------------- --}}
{{-- BLOKOVÝ EDITOR STRÁNKY --}}
{{-- -------------------------------------------------- --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('page-form');
    if (!form) return;

    const mode = form.dataset.editorMode || 'html';
    if (mode !== 'blocks') return;

    const blocksRoot      = document.getElementById('json-blocks');
    const contentTextarea = document.getElementById('json-content');

    if (!blocksRoot || !contentTextarea) return;

    form.addEventListener('submit', function () {

        const cards = blocksRoot.querySelectorAll('.card');
        if (!cards.length) return; // ochrana proti []

        const blocks = [];

        cards.forEach(card => {
            const select = card.querySelector('select');
            if (!select) return;

            const block = { type: select.value, columns: {} };

            card.querySelectorAll('input[type="text"]').forEach(input => {
                const m = input.name.match(/\[columns]\[(.*?)\]/);
                if (m) block.columns[m[1]] = input.value;
            });

            if (block.type) blocks.push(block);
        });

        if (!blocks.length) return; // další ochrana

        contentTextarea.value = JSON.stringify(blocks, null, 2);
    });

});
</script>

{{-- -------------------------------------------------- --}}
{{-- OCHRANA PROTI [] V HTML REŽIMU --}}
{{-- -------------------------------------------------- --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById('page-form');
    if (!form) return;

    form.addEventListener('submit', function (e) {

        const mode = form.dataset.editorMode || 'html';
        const textarea = document.querySelector('textarea[name="content"]');
        if (!textarea) return;

        const value = (textarea.value || '').trim();

        if (mode === 'html' && value === '[]') {
            e.preventDefault();
            alert('Obsah byl přepsán na [] — uložení zastaveno.');
        }
    });

});
</script>