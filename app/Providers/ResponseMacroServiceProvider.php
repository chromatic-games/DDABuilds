<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResponseMacroServiceProvider extends ServiceProvider {
	/**
	 * Register the application's response macros.
	 *
	 * @return void
	 */
	public function boot() {
		Response::macro('apiResponse', [$this, 'apiResponse']);
		Response::macro('apiBadRequest', [$this, 'apiBadRequest']);
		Response::macro('apiMissingAuthorization', [$this, 'apiMissingAuthorization']);
	}

	/**
	 * send an error response with the status code, error and a custom message
	 *
	 * @param string $message
	 * @param int    $statusCode
	 *
	 * @return JsonResponse
	 */
	public function apiResponse(string $message, $statusCode = SymfonyResponse::HTTP_BAD_REQUEST) {
		$response = [
			'error'  => SymfonyResponse::$statusTexts[$statusCode],
			'status' => $statusCode,
		];

		if ( !empty($message) ) {
			$response['message'] = $message;
		}

		return response()->json($response, $statusCode);
	}

	/**
	 * send a missing authorization response
	 *
	 * @return JsonResponse
	 */
	public function apiMissingAuthorization() {
		return $this->apiResponse('Missing authorization', SymfonyResponse::HTTP_UNAUTHORIZED);
	}

	/**
	 * send a bad request response
	 *
	 * @param string $message
	 *
	 * @return JsonResponse
	 */
	public function apiBadRequest(string $message = '') {
		return $this->apiResponse($message, SymfonyResponse::HTTP_BAD_REQUEST);
	}
}