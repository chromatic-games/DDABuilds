<?php

namespace App\Http\Middleware;

class IsAuthenticated {
	public function handle($request, \Closure $next, string $role = 'guest') {
		if ( $role === 'guest' && auth()->id() || $role === 'user' && !auth()->id()) {
			return response()->apiResponse();
		}

		return $next($request);
	}
}