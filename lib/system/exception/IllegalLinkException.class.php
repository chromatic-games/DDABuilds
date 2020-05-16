<?php

namespace system\exception;

use system\Core;

/**
 * IllegalLinkException shows the unknown link error page.
 */
class IllegalLinkException extends NamedUserException {
	/**
	 * Creates a new IllegalLinkException object.
	 */
	public function __construct() {
		parent::__construct('Sorry, but the page you are looking for has not been found. Try checking the URL for errors, then hit the refresh button on your browser.');
	}

	/**
	 * @inheritDoc
	 */
	public function show() {
		@header('HTTP/1.1 404 Not Found');

		Core::getTPL()->assign([
			'title' => 'Page not Found',
		]);

		parent::show();
	}
}
