<?php

namespace system\exception;

use Exception;

abstract class UserException extends Exception {
	/**
	 * @inheritDoc
	 */
	public function show() {
		if ( DEBUG_MODE ) {
			echo '<pre>'.$this->getTraceAsString().'</pre>';
		}
		else {
			echo '<pre>'.$this->_getMessage().'</pre>';
		}
	}

	/**
	 * Returns the exception's message, should be used to sanitize the output.
	 *
	 * @return    string
	 */
	protected function _getMessage() {
		return $this->getMessage();
	}
}