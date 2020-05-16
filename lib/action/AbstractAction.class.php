<?php

namespace action;

use Exception;
use system\Core;
use system\exception\PermissionDeniedException;

abstract class AbstractAction {
	/** @var bool */
	public $loginRequired = false;

	/**
	 * @var mixed
	 */
	public $response;

	/**
	 * @throws Exception
	 */
	public function __run() {
		$this->readParameters();
		$this->execute();
	}

	/**
	 * read and validate parameters
	 */
	public function readParameters() {
	}

	/**
	 * execute the action
	 *
	 * @throws Exception
	 */
	public function execute() {
		if ( $this->loginRequired && !Core::getUser()->steamID ) {
			throw new PermissionDeniedException();
		}
	}
}