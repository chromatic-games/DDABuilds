<?php

namespace action;

use system\Core;
use system\exception\AJAXException;
use system\exception\IllegalLinkException;
use system\exception\NamedUserException;
use system\exception\PermissionDeniedException;
use system\exception\UserInputException;

class AjaxAction extends AbstractAction {
	/**
	 * @inheritDoc
	 */
	public function __run() {
		// execute action
		try {
			parent::__run();
		} catch ( \Exception $e ) {
			if ( $e instanceof AJAXException ) {
				throw $e;
			}
			else {
				$this->throwException($e);
			}
		} catch ( \Throwable $e ) {
			if ( $e instanceof AJAXException ) {
				throw $e;
			}
			else {
				$this->throwException($e);
			}
		}

		// send JSON-encoded response
		$this->sendResponse();
	}

	/**
	 * Throws an previously caught exception while maintaining the propriate stacktrace.
	 *
	 * @param \Exception|\Throwable $e
	 *
	 * @throws    \Exception
	 * @throws    \Throwable
	 */
	protected function throwException($e) {
		if ( $e instanceof PermissionDeniedException ) {
			throw new AJAXException('You are not authorized to execute this action.', AJAXException::INSUFFICIENT_PERMISSIONS, $e->getTraceAsString());
		}
		elseif ( $e instanceof IllegalLinkException ) {
			throw new AJAXException('The server was unable to process your request because the target is unknown or no longer available.', AJAXException::ILLEGAL_LINK, $e->getTraceAsString());
		}
		elseif ( $e instanceof NamedUserException ) {
			throw new AJAXException($e->getMessage(), AJAXException::BAD_PARAMETERS, $e->getTraceAsString());
		}
		elseif ( $e instanceof UserInputException ) {
			throw new AJAXException($e->getMessage(), AJAXException::BAD_PARAMETERS, $e->getTraceAsString(), [
				'errorMessage'     => '',
				'fieldName'        => $e->getFieldName(),
				'realErrorMessage' => $e->getErrorMessage(),
			]);
		}
		else {
			throw new AJAXException($e->getMessage(), AJAXException::INTERNAL_ERROR, $e->getTraceAsString());
		}
	}

	/**
	 * Sends JSON-Encoded response.
	 */
	protected function sendResponse() {
		if ( DEBUG_MODE ) {
			$this->response['benchmark'] = [
				'sqlQueries' => Core::getDB()->getQueryCount(),
			];
		}

		header('Content-type: application/json');
		die(json_encode($this->response));
	}
}