<?php

namespace App\Http\Middleware;

use Closure;
use Debugbar;

class HandleCountryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        config(['app.country' => $request->route('country')]);
        $request->route()->forgetParameter('country');
        Debugbar::addMessage(config('app.country'), 'app.country');
        return $next($request);
    }
}
