<meta charset="utf-8">
<title>@yield('title', 'CMS')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="/favicon.ico">

<!-- Bootstrap 5.3.3 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- Font Awesome (ikony) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css?family=Titillium+Web:300,300italic,400,400italic,700,700italic&subset=latin,latin-ext"
    rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;700&display=swap" rel="stylesheet">

<!-- DocSearch (volitelné) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">

<!-- Ikony z vlastního adresáře -->
<link rel="stylesheet" href="{{ asset('/iconpro/css/all.css') }}">

<!-- Mizzle šablona -->
<link rel="stylesheet" href="/template/mizzle/css/swiper-bundle.min.css" media="screen">
<link rel="stylesheet" href="/template/mizzle/css/style.css" media="screen">
<link rel="stylesheet" href="/template/mizzle/css/bugy.css" media="screen">
<script src="/template/mizzle/js/bugy.js"></script>



<!-- Dark mode -->
<script>
    const storedTheme = localStorage.getItem('theme')

    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme
        }
        return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light'
    }

    const setTheme = function (theme) {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme)
        }
    }

    setTheme(getPreferredTheme())

    window.addEventListener('DOMContentLoaded', () => {
        var el = document.querySelector('.theme-icon-active');
        if (el != 'undefined' && el != null) {
            const showActiveTheme = theme => {
                const activeThemeIcon = document.querySelector('.theme-icon-active use')
                const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active')
                })

                btnToActive.classList.add('active')
                activeThemeIcon.setAttribute('href', svgOfActiveBtn)
            }

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (storedTheme !== 'light' || storedTheme !== 'dark') {
                    setTheme(getPreferredTheme())
                }
            })

            showActiveTheme(getPreferredTheme())

            document.querySelectorAll('[data-bs-theme-value]')
                .forEach(toggle => {
                    toggle.addEventListener('click', () => {
                        const theme = toggle.getAttribute('data-bs-theme-value')
                        localStorage.setItem('theme', theme)
                        setTheme(theme)
                        showActiveTheme(theme)
                    })
                })

        }
    })

</script>


<style>
    .save-success {
        background-color: #c8e6c9;
        /* Zelené pozadí */
    }

    .save-error {
        background-color: #ffcdd2;
        /* Červené pozadí */
    }


    #backgroundCanvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        opacity: 1;
        pointer-events: none;
        /* Umožní průchod myší na text */
    }
</style>





<style>
    .carousel-item {
        position: relative;
        height: 500px;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        overflow: hidden;
    }

    .carousel-item::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        height: 20px;
        width: 100%;
        background-image:
            linear-gradient(-135deg, white 10px, transparent 0),
            linear-gradient(135deg, white 10px, transparent 0);
        background-position: left top;
        background-repeat: repeat-x;
        background-size: 20px 20px;
        z-index: 2;
    }


    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        /* průhledné ztmavení */
        z-index: 1;
    }

    .carousel-item .container {
        position: relative;
        z-index: 2;
    }


    .text-shadow {
        text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.9);
    }


    .overlay {
        background: linear-gradient(to bottom, rgba(0, 0, 0, 0.0), rgba(0, 0, 0, 0.6));
    }
</style>
<style>
    .image-hover-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 0.3rem;
        cursor: pointer;
    }

    .image-hover-wrapper img {
        transition: all 0.3s ease;
        display: block;
        width: 100%;
    }

    .image-hover-wrapper .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.35);
        opacity: 0;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-hover-wrapper .overlay i {
        font-size: 2rem;
        color: white;
    }

    .image-hover-wrapper:hover .overlay {
        opacity: 1;
    }

    .image-hover-wrapper:hover img {
        transform: scale(1.03);
        filter: brightness(80%);
    }
</style>


<style>
    tr.table-level-0 td {
        background-color: #f8f9fa !important;
    }

    tr.table-level-1 td {
        background-color: #e2f0d9 !important;
    }

    tr.table-level-2 td {
        background-color: #d1e7dd !important;
    }

    tr.table-level-3 td {
        background-color: #cfe2ff !important;
    }

    tr.table-level-4 td {
        background-color: #e2e3e5 !important;
    }
</style>


<style>
    .nested-sortable .list-group-item {
        margin-bottom: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #f8f9fa;
        padding: 10px 15px;
    }

    .nested-sortable {
        padding-left: 15px;
    }
</style>


<style>
    .navbar .dropdown-toggle::after {
        display: inline-block !important;
        margin-left: .255em;
        vertical-align: .255em;
        content: "" !important;
        border-top: .3em solid;
        border-right: .3em solid transparent;
        border-bottom: 0;
        border-left: .3em solid transparent;
    }
</style>