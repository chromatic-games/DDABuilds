<?php

namespace wcf\system\exception;

/**
 * Exception implementation for cases when a class is expected to have a certain class
 * as a parent class but that is not the case.
 */
class ParentClassException extends \LogicException {
	/**
	 * ImplementationException constructor.
	 *
	 * @param string $className
	 * @param string $parentClassName
	 */
	public function __construct($className, $parentClassName) {
		parent::__construct("Class '{$className}' does not extend class '{$parentClassName}'.");
	}
}
