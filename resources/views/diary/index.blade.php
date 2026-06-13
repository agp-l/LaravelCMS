@extends('layouts.default.app') {{-- Uprav dle svého rozvržení --}}

@section('title', 'Cestovní deník')

@section('content')

{{-- Načtení ikon pro deník --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">

<style>
    /* TVOJE CSS PRO TIMELINE */
    .cbp_tmtimeline { margin: 0; padding: 0; list-style: none; position: relative }
    .cbp_tmtimeline:before { content: ''; position: absolute; top: 0; bottom: 0; width: 3px; background: #e2e8f0; left: 20%; margin-left: -6px }
    .cbp_tmtimeline>li { position: relative }
    .cbp_tmtimeline>li:first-child .cbp_tmtime span.large { color: #444; font-size: 17px !important; font-weight: 700 }
    .cbp_tmtimeline>li:first-child .cbp_tmicon { background: #fff; color: #666 }
    .cbp_tmtimeline>li:nth-child(odd) .cbp_tmtime span:last-child { color: #444; font-size: 13px }
    .cbp_tmtimeline>li:nth-child(odd) .cbp_tmlabel { background: #f8fafc }
    .cbp_tmtimeline>li:nth-child(odd) .cbp_tmlabel:after { border-right-color: #f8fafc }
    .cbp_tmtimeline>li .cbp_tmtime { display: block; width: 23%; padding-right: 70px; position: absolute }
    .cbp_tmtimeline>li .cbp_tmtime span { display: block; text-align: right }
    .cbp_tmtimeline>li .cbp_tmtime span:first-child { font-size: 15px; color: #1e293b; font-weight: 700 }
    .cbp_tmtimeline>li .cbp_tmtime span:last-child { font-size: 14px; color: #64748b }
    .cbp_tmtimeline>li .cbp_tmlabel { margin: 0 0 15px 25%; background: #ffffff; border: 1px solid #e2e8f0; padding: 1.5em; position: relative; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .cbp_tmtimeline>li .cbp_tmlabel:after { right: 100%; border: solid transparent; content: " "; height: 0; width: 0; position: absolute; pointer-events: none; border-right-color: #ffffff; border-width: 10px; top: 15px }
    .cbp_tmtimeline>li .cbp_tmicon { width: 40px; height: 40px; font-size: 1.4em; line-height: 40px; position: absolute; color: #fff; border-radius: 50%; box-shadow: 0 0 0 5px #f8fafc; text-align: center; left: 20%; top: 5px; margin: 0 0 0 -25px }
    
    @media screen and (max-width: 47.2em) {
        .cbp_tmtimeline:before { display: none }
        .cbp_tmtimeline>li .cbp_tmtime { width: 100%; position: relative; padding: 0 0 10px 0 }
        .cbp_tmtimeline>li .cbp_tmtime span { text-align: left; display: inline-block; margin-right: 10px; }
        .cbp_tmtimeline>li .cbp_tmlabel { margin: 0 0 30px 0; padding: 1.2em; }
        .cbp_tmtimeline>li .cbp_tmlabel:after { display: none; }
        .cbp_tmtimeline>li .cbp_tmicon { position: relative; float: right; left: auto; margin: -55px 5px 0 0px }
    }
</style>

<div class="container py-5">
    
    <div class="text-center mb-5 mx-auto" style="max-width: 800px;">
        <h1 class="display-5 fw-bold mb-3">Cestovní deník</h1>
        <p class="lead text-muted mb-4">
            „S touhou po dobrodružství opouštím známé břehy a vydávám se do neznámých hlubin Jižní Ameriky. Den za dnem, s pouhým dolarem mezi přežitím a hladem, němý v zemi, kde i vítr šeptá v cizí řeči.“ Přežiji tenhle bláznivý trip?
        </p>
        <div>
            <a class="btn btn-outline-secondary mx-1" href="https://www.instagram.com/agpl_teepek/" target="_blank"><i class="fa-brands fa-instagram me-1"></i> Instagram</a>
            <a class="btn btn-outline-secondary mx-1" href="https://www.youtube.com/@na-%C3%BAt%C4%9Bku" target="_blank"><i class="fa-brands fa-youtube me-1"></i> Youtube</a>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <ul class="cbp_tmtimeline" id="posts-container">
                
                <li>
                    <time class="cbp_tmtime" datetime="{{ now()->toIso8601String() }}">
                        <span>Řazení</span>
                        <span>Od nejnovějších</span>
                    </time>
                    <div class="cbp_tmicon bg-dark"><i class="zmdi zmdi-account"></i></div>
                    <div class="cbp_tmlabel empty border-0 bg-transparent shadow-none"> 
                        <span class="fst-italic text-muted">Ať už prší či svítí slunce, každý týden vám přinesu alespoň jeden příběh z cest, plný dobrodružství a objevů.</span>
                    </div>
                </li>

                @foreach($posts as $post)
                    <li>
                        <time class="cbp_tmtime" datetime="{{ $post->created_at->toIso8601String() }}">
                            <span>{{ $post->created_at->format('H:i') }}</span>
                            <span>{{ $post->created_at->format('j. n. Y') }}</span>
                        </time>
                        
                        <div class="cbp_tmicon {{ $post->icon_color_class }}"> 
                            <i class="{{ $post->icon_class }}"></i>
                        </div>
                        
                        <div class="cbp_tmlabel">
                            <div class="editable-element text-dark" data-id="{{ $post->id }}">
                                {{-- Vykreslíme zkonvertovaný obsah (značky img, url, b atd.) --}}
                                {!! $post->parsed_content !!}
                            </div>
                            
                            {{-- Pokud je u postu připojená mapa, zobrazíme odkaz --}}
                            @if($post->map_url && $post->map_url !== 'none')
                                <div class="mt-3">
                                    <a href="{{ $post->map_url }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                                        <i class="zmdi zmdi-pin me-1"></i> Zobrazit na mapě
                                    </a>
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach

            </ul>

            <div class="d-flex justify-content-center gap-2 mt-5">
                {{ $posts->links() }} {{-- Toto automaticky vygeneruje Laravel stránkování --}}
            </div>

        </div>
    </div>
</div>

@endsection