<!-- resources/views/home.blade.php -->
@extends('layouts.app')

@section('title', 'Blog - články')

@section('content')
<div class="container">
    <section class="article my-5">
        <div class="row">
            <div class="col-md-2"></div>

            <div class="col-md-6 px-2" style="text-align: justify; text-justify: inter-character;">
                @if ($articles->isNotEmpty())
                    @php $article = $articles->first(); @endphp
                    <h2>{{ $article->title }}</h2>
                    <div class="lead mb-4">{!! $article->content !!}</div>
                    <a href="{{ route('article.show', ['slug' => $article->slug]) }}">Zobrazit celou stránku</a>
                @else
                    <p>Momentálně není žádný článek k dispozici.</p>
                @endif
            </div>

            <div class="col-md-1"></div>

            <div class="col-md-3">
                <div id="list" class="list-group mb-4">
                    <a class="list-group-item list-group-item-primary" href="#">Poslední články</a>
                    @foreach ($articles->take(10) as $item)
                        <a class="list-group-item list-group-item-action" href="{{ route('article.show', ['slug' => $item->slug]) }}">
                            {{ $item->title }}
                        </a>
                    @endforeach
                </div>

                <div class="article px-2">
                    <p class="mt-5">PROPAGACE A ZMĚNA</p>
                    <span class="fw-light lh-base">
                        <p>Mým cílem je šíření povědomí o sebeřízeném vzdělávání, propaguji cesty jak obcházet diktát většiny, nabízím prostředky a příležitosti ke svobodnému vzdělávání.</p>
                    </span>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection