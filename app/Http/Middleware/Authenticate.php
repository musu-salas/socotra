<?php

namespace App\Http\Middleware;

use Closure;
use Config;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('login');
            }
        }

        if (Auth::user()->email === Config::get('services.facebook.empty_email') &&
            $request->path() !== 'home/settings') {

            return redirect('home/settings?' . http_build_query([
                    'followTo' => $request->path()
                ]));
        }

        return $next($request);
    }
}
