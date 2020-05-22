<?php

namespace system\exception;

use system\util\StringUtil;

class DatabaseQueryExecutionException extends DatabaseException {
	/**
	 * Parameters that were passed to execute().
	 * @var array
	 */
	protected $parameters = [];

	/**
	 * @inheritDoc
	 */
	public function __construct($message, $parameters, \PDOException $previous = null) {
		parent::__construct($message, $previous);

		$this->parameters = $parameters;
	}

	/**
	 * Returns the parameters that were passed to execute().
	 *
	 * @return array
	 */
	public function getParameters() {
		return $this->parameters;
	}

	/**
	 * Returns an array of (key, value) tuples with extra information to show
	 * in the human readable error log.
	 * Avoid including sensitive information (such as private keys or passwords).
	 *
	 * @return	mixed[][]
	 */
	public function getExtraInformation() {
		$i = 0;

		return array_map(function ($val) use (&$i) {
			switch ( gettype($val) ) {
				case 'NULL':
					$val = 'null';
					break;
				case 'string':
					$val = "'".addcslashes(StringUtil::encodeHTML($val), "\\'")."'";
					break;
				case 'boolean':
					$val = $val ? 'true' : 'false';
					break;
			}

			return ['Query Parameter '.(++$i), $val];
		}, $this->getParameters());
	}
}