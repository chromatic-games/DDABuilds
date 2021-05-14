<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler {
	/** @inheritDoc */
	public function render($request, Throwable $e) {
		$response = parent::render($request, $e);

		if ( $response instanceof JsonResponse ) {
			// add status and error code (if a description exists for this status code)
			$data = [
				'status' => $response->getStatusCode(),
			];
			if ( isset(Response::$statusTexts[$response->getStatusCode()]) ) {
				$data['error'] = Response::$statusTexts[$response->getStatusCode()];
			}

			// merge new data with old data
			$data = array_merge($response->getData(true), $data);

			if ( $e instanceof ModelNotFoundException ) {
				$data['message'] = '';
			}

			$response->setData($data);
		}

		return $response;
	}
}