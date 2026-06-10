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
/* 
    background-color: #0dcaf0 !important;
}


.text-primary {
    color: #0dcaf0 !important;
}



.btn-primary {
    background-color: #0dcaf0 !important;
    border-color: #0dcaf0 !important;
    color: #000 !important;
}


.btn-primary:hover {
    background-color: #31d2f2 !important;
    border-color: #25cff2 !important;
    color: #000 !important;
    
    text-decoration: none; 
}


a {
    color: #0dcaf0 !important;
    
}

*/

</style>