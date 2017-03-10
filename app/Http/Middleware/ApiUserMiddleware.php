<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiUserMiddleware
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
		$user = $request->route('user');

		if (Auth::guard($guard)->check() && $user->id === Auth::id()) {
			return $next($request);
		}

        return response()->json([
            'errors' => [ 'You\'re not authorized for modifications.' ]
        ], 401);
	}
}
