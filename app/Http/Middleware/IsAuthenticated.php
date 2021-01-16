<?php

namespace App\Http\Middleware;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class IsAuthenticated {
	public function handle($request, \Closure $next, string $role = 'guest') {
		if ( $role === 'guest' && auth()->id() || $role === 'user' && !auth()->id() ) {
			throw new AccessDeniedHttpException();
		}

		return $next($request);
	}
}