<?php

namespace App\Http\Middleware;

use App;
use App\Group;
use Closure;
use Illuminate\Support\Facades\Auth;

class GroupMiddleware
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
        $group = Group::find($request->route('group'));

        if ($group) {
            App::instance(Group::class, $group);
            return $next($request);
        }

        return abort(404);
    }
}
