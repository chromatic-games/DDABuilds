<?php

namespace App\Http\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class InternalServerErrorHttpException extends HttpException {
	public function __construct(string $message = null, \Throwable $previous = null, array $headers = [], ?int $code = 0) {
		parent::__construct(500, $message, $previous, $headers, $code);
	}
}