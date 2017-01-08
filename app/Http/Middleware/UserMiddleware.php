<?php

namespace App\Http\Middleware;

use App;
use App\User;
use Closure;

class UserMiddleware
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
        if ($user = User::find($request->route('userId'))) {
            App::instance(User::class, $user);
            return $next($request);
        }

        return abort(404);
    }
}
