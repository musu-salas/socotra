<?php namespace App\Http\Middleware;

use Closure;
use App\Group;
use Illuminate\Support\Facades\Auth;

class ApiOwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
		if (Auth::guard($guard)->check()) {
            $group = $request->route('group');

			if ($group->user_id === Auth::id()) {
                return $next($request);
            }
		}

        return response()->json([
            'errors' => [ 'You\'re not authorized to edit this class.' ]
        ], 401);
	}

}
