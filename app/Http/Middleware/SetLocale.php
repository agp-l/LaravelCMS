<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->segment(1);

        if (in_array($locale, ['cs', 'en'])) {
            App::setLocale($locale);
        } else {
            App::setLocale(config('app.locale')); // fallback
        }
        

        return $next($request);
    }
}
