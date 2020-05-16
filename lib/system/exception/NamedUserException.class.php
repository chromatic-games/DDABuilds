<?php

namespace system\exception;

use system\Core;

/**
 * NamedUserException shows a (well) styled page with the given error message.
 */
class NamedUserException extends UserException {
	/**
	 * Shows a styled page with the given error message.
	 */
	public function show() {
		Core::getTPL()->assign([
			'name'         => get_class($this),
			'file'         => $this->getFile(),
			'line'         => $this->getLine(),
			'message'      => $this->getMessage(),
			'stacktrace'   => $this->getTraceAsString(),
			'templateName' => 'userException',
		]);
		Core::getTPL()->display('userException');
	}
}