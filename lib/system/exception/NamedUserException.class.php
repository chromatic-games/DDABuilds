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
		$stacktrace = $this->getTraceAsString();
		$previous = $this->getPrevious();
		while ( $previous ) {
			$stacktrace .= '<br /><br />'.$previous->getMessage().'<br />'.$previous->getTraceAsString();
			$previous = $previous->getPrevious();
		}

		Core::getTPL()->assign([
			'name'         => get_class($this),
			'file'         => $this->getFile(),
			'line'         => $this->getLine(),
			'message'      => $this->getMessage(),
			'stacktrace'   => $stacktrace,
			'templateName' => 'userException',
		]);
		Core::getTPL()->display('userException');
		exit;
	}
}