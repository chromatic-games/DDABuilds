<?php

namespace action;

use data\DatabaseObjectAction;
use system\Core;
use system\exception\AJAXException;
use system\exception\IllegalLinkException;
use system\exception\NamedUserException;
use system\exception\ParentClassException;
use system\exception\PermissionDeniedException;
use system\exception\UserInputException;
use system\util\ArrayUtil;
use system\util\StringUtil;

class AjaxAction extends AbstractAction {
	/**
	 * list of object ids
	 * @var integer[]
	 */
	public $objectIDs = [];

	/**
	 * additional parameters
	 * @var mixed[]
	 */
	public $parameters = [];

	/**
	 * @var string
	 */
	public $actionName;

	/**
	 * @var string
	 */
	public $className;

	/**
	 * @var DatabaseObjectAction
	 */
	public $objectAction;

	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( isset($_POST['objectIDs']) && is_array($_POST['objectIDs']) ) {
			$this->objectIDs = ArrayUtil::toIntegerArray($_POST['objectIDs']);
		}
		if ( isset($_POST['parameters']) && is_array($_POST['parameters']) ) {
			$this->parameters = $_POST['parameters'];
		}
		if ( isset($_POST['actionName']) ) {
			$this->actionName = StringUtil::trim($_POST['actionName']);
		}
		if ( isset($_POST['className']) ) {
			$this->className = StringUtil::trim($_POST['className']);
		}

		if (empty($this->className) || !class_exists($this->className)) {
			throw new UserInputException('className');
		}
	}

	/** @inheritDoc */
	public function execute() {
		if (!is_subclass_of($this->className, DatabaseObjectAction::class)) {
			throw new ParentClassException($this->className, DatabaseObjectAction::class);
		}

		$this->objectAction = new $this->className($this->objectIDs, $this->actionName, $this->parameters);
		$this->objectAction->validateAction();

		$this->response = $this->objectAction->executeAction();
	}

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
			throw new AJAXException($e->getMessage(), AJAXException::INTERNAL_ERROR, $e->getTraceAsString(), [], $e);
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