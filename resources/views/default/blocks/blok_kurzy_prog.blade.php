<section class="py-5 bg-light text-black rounded-4 my-5">
    <div class="container py-4">
        <div class="mx-auto text-center" style="max-width: 750px;">
            <i class="{{ $ikona ?? 'fa-solid fa-laptop-code' }} fs-1 text-info mb-3"></i>
            <h2 class="display-5 fw-bold text-black mb-4">
                {{ $nadpis ?? 'Nyní jsou nejžádanější kurzy programování' }}
            </h2>
            <p class="lead mb-5" style="font-size: 1.15rem;">
                {{ $text ?? 'Děti učím programovat, vytváříme počítačové hry, a trávíme čas ve zdravém prostředí.' }}
            </p>
            <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                <a href="{{ $odkaz1_url ?? 'https://dobrodruzi.cz/cs/stranka/kurzy-programovani' }}"
                    class="btn btn-info text-dark fw-bold btn-lg px-4 py-3 rounded-3 shadow-sm">
                    {{ $odkaz1_text ?? 'Více o kurzech »' }}
                </a>
                <a href="{{ $odkaz2_url ?? 'https://dobrodruzi.cz/rezervace/' }}"
                    class="btn btn-outline-dark btn-lg px-4 py-3 rounded-3">
                    {{ $odkaz2_text ?? 'Volné termíny »' }}
                </a>
            </div>
        </div>
    </div>
</section>