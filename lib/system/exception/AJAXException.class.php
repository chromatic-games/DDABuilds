<?php

namespace system\exception;

use Exception;

class AJAXException extends Exception {
	/**
	 * missing parameters
	 * @var    integer
	 */
	const MISSING_PARAMETERS = 400;

	/**
	 * session expired
	 * @var    integer
	 */
	const SESSION_EXPIRED = 401;

	/**
	 * insufficient permissions
	 * @var    integer
	 */
	const INSUFFICIENT_PERMISSIONS = 403;

	/**
	 * illegal link
	 * @var    integer
	 */
	const ILLEGAL_LINK = 404;

	/**
	 * bad parameters
	 * @var    integer
	 */
	const BAD_PARAMETERS = 412;

	/**
	 * internal server error
	 * @var    integer
	 */
	const INTERNAL_ERROR = 503;

	/**
	 * Throws a JSON-encoded error message
	 *
	 * @param string                $message
	 * @param integer               $errorType
	 * @param string                $stacktrace
	 * @param mixed[]               $returnValues
	 * @param \Exception|\Throwable $previous
	 */
	public function __construct($message, $errorType = self::INTERNAL_ERROR, $stacktrace = null, $returnValues = [], $previous = null) {
		if ( $stacktrace === null ) {
			$stacktrace = $this->getTraceAsString();
		}

		$responseData = [
			'code'         => $errorType,
			'message'      => $message,
			'returnValues' => $returnValues,
		];

		// include a stacktrace on debug mode
		if ( DEBUG_MODE ) {
			$responseData['previous'] = [];
			$responseData['stacktrace'] = explode("\n", $stacktrace);
			if ( method_exists($this, 'getExtraInformation') ) {
				$responseData['extra'] = $this->getExtraInformation();
			}
			while ( $previous ) {
				$data = ['message' => $previous->getMessage()];
				$data['stacktrace'] = explode("\n", $previous->getTraceAsString());

				$responseData['previous'][] = $data;
				$previous = $previous->getPrevious();
			}
		}

		$statusHeader = null;
		switch ( $errorType ) {
			case self::MISSING_PARAMETERS:
				$statusHeader = 'HTTP/1.1 400 Bad Request';
				$responseData['message'] = 'The server was unable to process your request due to an incomplete request.';
				break;

			case self::SESSION_EXPIRED:
				$statusHeader = 'HTTP/1.1 409 Conflict';
				break;

			case self::INSUFFICIENT_PERMISSIONS:
				$statusHeader = 'HTTP/1.1 403 Forbidden';
				break;

			case self::BAD_PARAMETERS:
				$statusHeader = 'HTTP/1.1 400 Bad Request';
				break;
			case self::ILLEGAL_LINK:
				$statusHeader = 'HTTP/1.1 404 Not Found';
				$responseData['code'] = self::ILLEGAL_LINK;
				break;

			default:
			case self::INTERNAL_ERROR:
				header('HTTP/1.1 503 Service Unavailable');
				$responseData['code'] = self::INTERNAL_ERROR;

				if ( !DEBUG_MODE ) {
					$responseData['message'] = 'The server encountered an unresolvable problem, please try again later.';
				}
				break;
		}

		if ( $statusHeader ) {
			header($statusHeader);
		}

		header('Content-type: application/json');
		die(json_encode($responseData));
	}
}