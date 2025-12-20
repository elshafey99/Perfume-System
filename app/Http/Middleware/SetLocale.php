<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check for locale in request (query param, header, or body)
        $locale = $request->input('lang')
            ?? $request->header('Accept-Language')
            ?? $request->header('X-Locale')
            ?? 'ar'; // Default to Arabic

        // Extract first two characters (e.g., 'en-US' -> 'en')
        $locale = substr($locale, 0, 2);

        // Validate locale
        if (!in_array($locale, ['ar', 'en'])) {
            $locale = 'ar';
        }

        // Set application locale
        App::setLocale($locale);

        return $next($request);
    }
}
