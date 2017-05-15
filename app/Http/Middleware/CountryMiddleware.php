<?php declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Debugbar;

class CountryMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(\Illuminate\Http\Request $request, Closure $next)
    {
        $country = $request->route('country');

        config(['app.country' => $country]);
        $request->route()->forgetParameter('country');
        Debugbar::addMessage(config('app.country'), 'app.country');

        return $next($request);
    }
}
