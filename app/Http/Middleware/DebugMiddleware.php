<?php

namespace App\Http\Middleware;

use App\Util\FileUtil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebugMiddleware {
	/**
	 * @param          $request
	 * @param \Closure $next
	 *
	 * @return JsonResponse
	 */
	public function handle(Request $request, \Closure $next) {
		/** @var JsonResponse $response */
		$response = $next($request);

		if ( $response instanceof JsonResponse ) {
			$data = $response->getData(true);
			$queries = DB::connection()->getQueryLog();
			$data['_debug'] = [
				'queryCount'    => count($queries),
				'queries'       => $queries,
				'memory'        => FileUtil::formatFilesize(memory_get_peak_usage(true)),
				'executionTime' => round(microtime(true) - LARAVEL_START, 2),
			];
			$response->setData($data);
		}

		return $response;
	}
}