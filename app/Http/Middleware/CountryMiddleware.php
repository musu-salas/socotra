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
        $route = $request->route();
        $country = $route->parameter('country');
        $defaultCountry = config('app.country');
        $countries = config('countries');

        if (!isset($countries[$country])) {
            // Redirect to www with 301 status
            return redirect(str_replace("//{$country}.", "//{$defaultCountry}.", $request->fullUrl()), 301);
        }

        config(['app.country' => $country]);
        Debugbar::addMessage(config('app.country'), 'app.country');
        $route->forgetParameter('country');

        return $next($request);
    }
}
