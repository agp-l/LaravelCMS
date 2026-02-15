<?php

return [

    // Uncomment the languages that your site supports - or add new ones.
    // These are sorted by the native name, which is the order you might show them in a language selector.
    // Regional languages are sorted by their base language, so "British English" sorts as "English, British"
'supportedLocales' => [
    'cs' => ['name' => 'Čeština', 'script' => 'Latn', 'native' => 'Čeština', 'regional' => 'cs_CZ'],
    'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    'es' => ['name' => 'Español', 'script' => 'Latn', 'native' => 'Español', 'regional' => 'es_ES'],
    'de' => ['name' => 'Deutsch', 'script' => 'Latn', 'native' => 'Deutsch', 'regional' => 'de_DE'],
    'fr' => ['name' => 'Français', 'script' => 'Latn', 'native' => 'Français', 'regional' => 'fr_FR'],
    'gn' => ['name' => 'Guarani', 'script' => 'Latn', 'native' => 'Avañe\'ẽ', 'regional' => 'gn_PY'],
    'py' => ['name' => 'Paraguayo', 'script' => 'Latn', 'native' => 'Paraguayo', 'regional' => 'es_PY'],
],


    // Requires middleware `LaravelSessionRedirect.php`.
    //
    // Automatically determine locale from browser (https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Accept-Language)
    // on first call if it's not defined in the URL. Redirect user to computed localized url.
    // For example, if users browser language is `de`, and `de` is active in the array `supportedLocales`,
    // the `/about` would be redirected to `/de/about`.
    //
    // The locale will be stored in session and only be computed from browser
    // again if the session expires.
    //
    // If false, system will take app.php locale attribute
    'useAcceptLanguageHeader' => false,

    // If `hideDefaultLocaleInURL` is true, then a url without locale
    // is identical with the same url with default locale.
    // For example, if `en` is default locale, then `/en/about` and `/about`
    // would be identical.
    //
    // If in addition the middleware `LaravelLocalizationRedirectFilter` is active, then
    // every url with default locale is redirected to url without locale.
    // For example, `/en/about` would be redirected to `/about`.
    // It is recommended to use `hideDefaultLocaleInURL` only in
    // combination with the middleware `LaravelLocalizationRedirectFilter`
    // to avoid duplicate content (SEO).
    //
    // If `useAcceptLanguageHeader` is true, then the first time
    // the locale will be determined from browser and redirect to that language.
    // After that, `hideDefaultLocaleInURL` behaves as usual.
    'hideDefaultLocaleInURL' => false,

    // If you want to display the locales in particular order in the language selector you should write the order here.
    //CAUTION: Please consider using the appropriate locale code otherwise it will not work
    //Example: 'localesOrder' => ['es','en'],
    'localesOrder' => [],

    // If you want to use custom language URL segments like 'at' instead of 'de-AT', you can map them to allow the
    // LanguageNegotiator to assign the desired locales based on HTTP Accept Language Header. For example, if you want
    // to use 'at' instead of 'de-AT', you would map 'de-AT' to 'at' (ie. ['de-AT' => 'at']).
    'localesMapping' => [],

    // Locale suffix for LC_TIME and LC_MONETARY
    // Defaults to most common ".UTF-8". Set to blank on Windows systems, change to ".utf8" on CentOS and similar.
    'utf8suffix' => env('LARAVELLOCALIZATION_UTF8SUFFIX', '.UTF-8'),

    // URLs which should not be processed, e.g. '/nova', '/nova/*', '/nova-api/*' or specific application URLs
    // Defaults to []
    'urlsIgnored' => ['/skipped'],

    'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
];
