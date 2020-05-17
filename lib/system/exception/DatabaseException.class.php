<?php

namespace system\exception;

class DatabaseException extends UserException {
	public function __construct($message = "", \PDOException $previous = null) {
		parent::__construct($message, 0, $previous);

		if ( $previous ) {
			$this->code = $previous->code;
		}
	}
}