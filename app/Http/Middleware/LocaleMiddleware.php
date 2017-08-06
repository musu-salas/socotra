<?php declare(strict_types=1);

namespace App\Http\Middleware;

use App;
use Closure;
use Debugbar;

class LocaleMiddleware
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
        $country = config('countries')[config('app.country')];
        $defaultLocale = $country['defaultLocale'];
        $domain = $request->root();
        $route = $request->route();
        $locale = ltrim($route->getPrefix(), '/');

        if ($locale === $defaultLocale) {
            // Redirects to the same route without locale, with 301 status
            return redirect(str_replace("{$domain}/{$locale}", $domain, $request->fullUrl()), 301);
        }

        App::setlocale($locale ? $locale : $defaultLocale);
        Debugbar::addMessage(config('app.locale'), 'app.locale');

        return $next($request);
    }
}
