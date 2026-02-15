
    <!-- Bootstrap Bundle (včetně Popper.js) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">



<!-- Theme Bootstrap JS -->
<script src="/template/mizzle/js/bootstrap.bundle.min.js"></script>
<script src="/template/mizzle/js/purecounter_vanilla.js"></script>
<script src="/template/mizzle/js/index.js"></script>
<script src="/template/mizzle/js/swiper-bundle.min.js"></script>
<script src="/template/mizzle/js/functions.js"></script>

    <!-- TinyMCE (z CDN) -->
    <script src="https://cdn.tiny.cloud/1/dcpt068f6z0iexoyf3nng4ck3m92hgfr53phm4opmcqv405v/tinymce/6/tinymce.min.js"
        referrerpolicy="origin"></script>
    <script>
        function slugify(text) {
            return text
                .toString()
                .toLowerCase()
                .normalize('NFD')                  // Odstraní diakritiku
                .replace(/[\u0300-\u036f]/g, '')   // Další diakritika
                .replace(/[^a-z0-9\s-]/g, '')      // Odstraní speciální znaky
                .trim()
                .replace(/\s+/g, '-')              // Mezera → pomlčka
                .replace(/-+/g, '-');              // Více pomlček → jedna
        }

        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('input', function () {
                    if (!slugInput.value || slugInput.value === slugify(slugInput.value)) {
                        slugInput.value = slugify(titleInput.value);
                    }
                });
            }
        });
    </script>


<script>
    function copyToClipboard(id) {
        const input = document.getElementById(id);
        navigator.clipboard.writeText(input.value).then(function() {
            alert('Adresa zkopírována: ' + input.value);
        }, function(err) {
            alert('Chyba při kopírování');
        });
    }
</script>


@if (!session('tinymce_disabled'))
    <script>
        tinymce.init({
            selector: 'textarea#content',
            plugins: 'lists link image code',
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright | bullist numlist | link image | code',
            menubar: false,
            height: 400,
            entity_encoding: 'raw',

            // 🔽 Tohle úplně vypne validaci a přepisování:
            verify_html: false,
            valid_elements: '*[*]',
            extended_valid_elements: '*[*]',
            valid_children: '+body[*]',
            forced_root_block: false, // pokud chceš povolit i fragmenty bez <p>

            // Volitelně:
            // content_css: false, // neaplikuje žádné výchozí styly

            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,


        });
    </script>
@endif







@if (!session('tinymce_disabled'))

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        console.log('🔄 Skript načten...');
    
        const form = document.getElementById('menu-order-form');
        const container = document.getElementById('nested-menu');
        const input = document.getElementById('order-data');
        const submitBtn = document.getElementById('submit-button');
        const nestedSortables = document.querySelectorAll('.nested-sortable');
    
        if (form) console.log('📋 Formulář nalezen.');
        else console.warn('❌ Formulář nenalezen!');
    
        if (container) console.log('📦 Kontejner pro menu nalezen.');
        else console.warn('❌ Kontejner pro menu nenalezen!');
    
        if (input) console.log('📝 Skrytý input nalezen.');
        else console.warn('❌ Skrytý input nenalezen!');
    
        if (submitBtn) {
            console.log('🧪 Submit tlačítko připraveno.');
            submitBtn.addEventListener('click', function () {
                console.log('🖱️ Kliknutí na submit tlačítko zaznamenáno.');
            });
        }
    
        if (nestedSortables.length > 0) {
            console.log(`✅ Nalezeno ${nestedSortables.length} tříd "nested-sortable".`);
            nestedSortables.forEach(el => {
                new Sortable(el, {
                    group: 'nested',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65
                });
                console.log('🔧 Sortable inicializován.');
            });
        } else {
            console.warn('❌ Žádný nested-sortable kontejner nenalezen.');
        }
    
        form?.addEventListener('submit', function (e) {
            //e.preventDefault();
            console.log('🚀 Formulář byl odeslán – zpracovávám pořadí...');
    
            const order = [];
    
            function traverse(listGroup, parentId = null) {
                const items = Array.from(listGroup.children).filter(el => el.classList.contains('list-group-item'));
    
                items.forEach((item, index) => {
                    const id = item.getAttribute('data-id');
                    if (id) {
                        order.push({ id: id, parent_id: parentId, order: index });
                    }
    
                    // najdi vnořenou skupinu
                    const nested = item.querySelector('.nested-sortable');
                    if (nested) {
                        traverse(nested, id);
                    }
                });
            }
    
            // začneme traversovat od každé nejvyšší .nested-sortable
            document.querySelectorAll('#nested-menu > .nested-sortable').forEach(group => {
                traverse(group, null);
            });
    
            console.log('📦 Vygenerované pořadí:', order);
            input.value = JSON.stringify(order);
            console.log('✅ Data zapsána do hidden inputu. 📝');
    
             form.submit(); // odkomentuj až bude vše OK
        });
    </script>
    




    <script>
        function confirmDelete(button) {
            if (!confirm('Opravdu smazat tento odkaz?')) return false;

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = button.closest('[data-form]').dataset.form;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = '{{ csrf_token() }}';
            form.appendChild(csrf);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();

            return false;
        }
    </script>


@endif