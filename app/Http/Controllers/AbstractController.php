<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\Response;

class AbstractController extends BaseController {
	/**
	 * send an error response with the status code, error and a custom message
	 *
	 * @param     $message
	 * @param int $statusCode
	 *
	 * @return JsonResponse
	 */
	protected function sendErrorResponse($message, $statusCode = Response::HTTP_BAD_REQUEST) {
		$response = [
			'error'   => Response::$statusTexts[$statusCode],
			'status'  => $statusCode,
		];

		if ( !empty($message) ) {
			$response['message'] = $message;
		}

		return response()->json($response, $statusCode);
	}

	/**
	 * send a bad request error response
	 *
	 * @param string $message
	 *
	 * @return JsonResponse
	 */
	protected function sendBadRequest(string $message = '') {
		return $this->sendErrorResponse($message, Response::HTTP_BAD_REQUEST);
	}
}