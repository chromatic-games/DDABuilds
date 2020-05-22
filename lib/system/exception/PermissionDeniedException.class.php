<?php

namespace system\exception;

use system\Core;

/**
 * A PermissionDeniedException is thrown when a user has no permission to access
 */
class PermissionDeniedException extends NamedUserException {
	/**
	 * Creates a new IllegalLinkException object.
	 */
	public function __construct() {
		parent::__construct('Access denied. You’re not authorized to view this page.');
	}

	/** @inheritDoc */
	public function show() {
		@header('HTTP/1.1 403 Forbidden');

		Core::getTPL()->assign([
			'title' => 'Access Denied',
		]);

		parent::show();
	}
}
