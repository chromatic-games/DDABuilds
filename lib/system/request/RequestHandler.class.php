<?php

namespace system\request;

use system\SingletonFactory;

class RequestHandler extends SingletonFactory {
	/** @var bool */
	public $isXMLHttpRequest = false;

	/** init request handler */
	protected function init() {
		$this->isXMLHttpRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
	}
}