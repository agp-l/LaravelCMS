<div class="dropdown dropdown-animation">
    <button class="btn" type="button" id="language" data-bs-toggle="dropdown">
        <i class="fa-thin fa-language"></i>
    </button>
    <ul class="dropdown-menu" aria-labelledby="language">
        @foreach ($languageLinks as $link)
            <li>
                <a class="dropdown-item" rel="alternate" hreflang="{{ $link['code'] }}" href="{{ $link['url'] }}">
                    <img src="{{ $link['flag'] }}" width="28" alt="{{ $link['label'] }}">
                    {{ $link['label'] }}
                </a>
            </li>
        @endforeach
    </ul>
</div>
