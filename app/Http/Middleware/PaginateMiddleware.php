<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaginateMiddleware {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure                 $next
	 *
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next) {
		$response = $next($request);

		if ( $response instanceof JsonResponse ) {
			$data = $response->getData(true);
			if ( $response->getOriginalContent() instanceof \Illuminate\Pagination\LengthAwarePaginator ) {
				$data = array_intersect_key($data, array_flip([
					'data',
					'current_page',
					'last_page',
					'total',
				]));
			}
			$response->setData($data);
		}

		return $response;
	}
}
