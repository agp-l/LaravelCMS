<meta charset="utf-8">
<title>@yield('title', 'CMS')</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" href="/favicon.ico">

<link rel="stylesheet" href="{{ asset('font/css/fontawesome.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/solid.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/regular.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/brands.css') }}">
<link rel="stylesheet" href="{{ asset('font/css/duotone.css') }}">

<!-- Bootstrap 5 CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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





    /* Obecný styl pro hlavní navigační odkazy */
    .navbar-nav .nav-link {
        color: rgb(62, 189, 239);
        /* Bootstrap modrá */
    }


    /* Hover změna barvy textu */
    .navbar-nav .nav-link:hover {
        color: rgb(0, 0, 0);
        /* tmavší modrá */
    }


    .bg-dark .navbar-nav .nav-link {
        color: #ffffff;
    }

    .bg-dark .navbar-nav .nav-link:hover {
        color: #cccccc;
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
    .feature-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
    }

    .feature-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
    }

    .bg-soft-primary {
        background-color: #e8f1ff;
    }

    .bg-soft-success {
        background-color: #e6f8f3;
    }

    .bg-soft-warning {
        background-color: #fff8e9;
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
    }

    .section-heading {
        font-weight: 700;
        background: linear-gradient(120deg, #11c7ec, #0fc4d8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }


    .icon-box {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }



    .card-img-top {
    height: 200px;
    object-fit: cover;
}
</style>

<style>
    /* ===== Mobilní zarovnání textu ===== */
@media (max-width: 767.98px) {

 

      .lead {
        font-size: 1rem !important;   /* standardní velikost textu */
        font-weight: 400 !important;  /* zruší lehké zvýraznění */
        line-height: 1.6;
         justify-content: flex-start !important;
    }


    }
</style>

<style>
/* KARTY AKTIVIT */
.activity-card {
    border: 2px solid #e2e8f0;
    border-radius: 1rem;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #ffffff;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.activity-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
    border-color: #cbd5e1;
}

/* Aktivní stav karty - barva se pak bude měnit dynamicky podle DB */
.activity-card.active {
    border-color: var(--theme-color, #0d6efd);
    background-color: #f8fafc;
    box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
}

.activity-icon {
    font-size: 2rem;
    margin-bottom: 1rem;
    color: var(--theme-color, #64748b);
}

.activity-price-tag {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #f1f5f9;
    padding: 0.25rem 0.75rem;
    border-radius: 2rem;
    font-size: 0.85rem;
    font-weight: 700;
    color: #475569;
}

.activity-schedule-tag {
    display: inline-block;
    margin-top: 1rem;
    font-size: 0.8rem;
    background: #e0f2fe;
    color: #0369a1;
    padding: 0.4rem 0.8rem;
    border-radius: 0.5rem;
    font-weight: 600;
}

/* KALENDÁŘ - KROK 2 */
.step-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.02);
}

.step-title {
    display: flex;
    align-items: center;
    font-weight: 800;
    margin-bottom: 1.5rem;
    color: #1e293b;
}

.step-number {
    background: #1e293b;
    color: #ffffff;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 1rem;
    font-size: 1rem;
}

/* Tlačítka dnů v kalendáři */
.day-btn {
    min-width: 80px;
    padding: 0.75rem;
    border: 2px solid #e2e8f0;
    border-radius: 0.75rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s;
    background: white;
}

/* Nedostupné dny pro danou aktivitu */
.day-btn.disabled {
    opacity: 0.4;
    background: #f8fafc;
    cursor: not-allowed;
    border-style: dashed;
}

/* Dostupný den */
.day-btn.available:hover {
    border-color: var(--theme-color, #0d6efd);
    background: #f8fafc;
}

/* Vybraný den */
.day-btn.active {
    background: var(--theme-color, #0d6efd);
    border-color: var(--theme-color, #0d6efd);
    color: white !important;
}

.day-btn.active span, .day-btn.active strong {
    color: white !important;
}

/* HODINY A FORMULÁŘ - KROK 3 */
.slot-checkbox-label {
    display: block;
    cursor: pointer;
}

.slot-checkbox-label input {
    display: none;
}

.slot-box {
    border: 2px solid #e2e8f0;
    padding: 1rem;
    border-radius: 0.75rem;
    text-align: center;
    font-weight: 700;
    color: #475569;
    transition: all 0.2s;
}

.slot-checkbox-label input:checked + .slot-box {
    background: var(--theme-color, #0d6efd);
    border-color: var(--theme-color, #0d6efd);
    color: white;
}

.slot-checkbox-label.disabled .slot-box {
    opacity: 0.5;
    background: #f1f5f9;
    text-decoration: none;
    border-style: dashed;
    cursor: not-allowed;
}


/* Plynulé scrollování a skrytí scrollbaru pro kalendář */
.hide-scroll {
    scroll-behavior: smooth;
    -ms-overflow-style: none;  /* IE a Edge */
    scrollbar-width: none;  /* Firefox */
}
.hide-scroll::-webkit-scrollbar {
    display: none; /* Chrome, Safari a Opera */
}
.day-btn {
    flex: 0 0 auto; /* Zabrání smrsknutí tlačítek dnů k sobě */
}

/* Oprava hover efektu pro aktivní den, aby text nezmizel */
.day-btn.active:hover {
    background: var(--theme-color, #0d6efd) !important;
    border-color: var(--theme-color, #0d6efd) !important;
}

.day-btn.active:hover span, 
.day-btn.active:hover strong {
    color: white !important;
}

</style>

<style>
    /* Vlastní responzivní třída pro karty */
    .card-responsive {
        background-color: #ffffff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Odpovídá shadow-sm */
        border-radius: 1rem; /* Odpovídá rounded-4 */
        height: 100%;
        padding: 1.5rem; /* Odpovídá p-4 */
    }
    
    /* Pravidlo pro PC a velké tablety (od 992px výše) */
    @media (min-width: 992px) {
        .card-responsive {
            background-color: transparent !important;
            box-shadow: none !important;
        }
    }
</style>