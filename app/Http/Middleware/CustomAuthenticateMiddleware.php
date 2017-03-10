<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CustomAuthenticateMiddleware
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
        $user = $request->user();
        $hasEmptyEmail = $user->email === config('services.facebook.empty_email');
        $urlPath = $request->path();

        if ($hasEmptyEmail && $urlPath !== 'home/settings') {
            $urlQuery = http_build_query([
                'followTo' => $urlPath
            ]);

            return redirect("home/settings?{$urlQuery}");
        }

        return $next($request);
    }
}
