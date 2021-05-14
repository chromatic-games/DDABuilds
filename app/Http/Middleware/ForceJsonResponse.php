<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Middleware to set the accept header to application/json, to force a json response
 *
 * @package App\Http\Middleware
 */
class ForceJsonResponse {
	/**
	 * @param Request $request
	 * @param Closure $next
	 *
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next) {
		$request->headers->set('Accept', 'application/json');

		return $next($request);
	}
}