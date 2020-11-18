<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class Handler extends ExceptionHandler {
	/** @inheritDoc */
	public function render($request, Throwable $e) {
		if ( $request->wantsJson() ) {
			$response = [
				'status' => 500,
			];

			if ( $this->isHttpException($e) ) {
				$response['status'] = $e->getStatusCode();
			}
			elseif ( $e instanceof AuthorizationException ) {
				$response['status'] = 403;
			}

			$response['error'] = SymfonyResponse::$statusTexts[$response['status']];

			if ( config('app.debug') ) {
				$response['exception'] = $this->convertExceptionToArray($e);
			}

			return response()->json($response, $response['status']);
		}

		return parent::render($request, $e);
	}
}