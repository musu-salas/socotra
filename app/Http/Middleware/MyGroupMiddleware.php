<?php

namespace App\Http\Middleware;

use App\Group;
use Closure;
use Illuminate\Support\Facades\Auth;

class MyGroupMiddleware
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
        $group = $request->route('group');

        if ($group->user_id !== Auth::id()) {
            return redirect()->to('/home/classes');
        }

        return $next($request);
    }
}
