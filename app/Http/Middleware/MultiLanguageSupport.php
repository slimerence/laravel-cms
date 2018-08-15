<?php

namespace App\Http\Middleware;

use Closure;

class MultiLanguageSupport
{
    /**
     * Fetch prefer language key from session and set to the app locale.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        app()->setLocale(
            $request->session()->get('prefer-lang','en')
        );
        return $next($request);
    }
}
