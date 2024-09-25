<?php

namespace Jaffran\LaravelTools\Middleware;

use Illuminate\Http\Request;
use Closure;

class LocaleHeader
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($request->header('Accept-Language', 'en'));
        return $next($request);
    }
}
