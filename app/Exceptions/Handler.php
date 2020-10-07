<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler {
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	public function render($request, Throwable $e) {
		if ( $request->is('api/*') ) {
			return $this->convertExceptionToArray($e);
		}

		if ( $this->isHttpException($e) ) {
			if ( $e->getStatusCode() === 404 ) {
				return response()->view('index');
			}
		}

		return parent::render($request, $e);
	}
}
