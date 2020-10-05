<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException as SymfonyBadRequestHttpException;

class BadRequestHttpException extends SymfonyBadRequestHttpException {
	/** @inheritdoc */
	public function __construct(string $message = 'Bad Request', \Throwable $previous = null, int $code = 0, array $headers = []) {
		parent::__construct($message, $previous, $code, $headers);
	}
}