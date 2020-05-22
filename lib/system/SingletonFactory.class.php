<?php
namespace system;

use Exception;

/**
 * Base class for singleton factories.
 */
abstract class SingletonFactory {
	/**
	 * list of singletons
	 * @var	SingletonFactory[]
	 */
	protected static $__singletonObjects = [];

	/**
	 * Singletons do not support a public constructor. Override init() if
	 * your class needs to initialize components on creation.
	 */
	protected final function __construct() {
		$this->init();
	}

	/**
	 * Called within __construct(), override if necessary.
	 */
	protected function init() { }

	/**
	 * Object cloning is disallowed.
	 */
	protected final function __clone() { }

	/**
	 * Object serializing is disallowed.
	 */
	public final function __sleep() {
		throw new Exception('Serializing of Singletons is not allowed'); // TODO replace to SystemException
	}

	/**
	 * Returns an unique instance of current child class.
	 *
	 * @return    static
	 */
	public static final function getInstance() {
		$className = get_called_class();
		if (!array_key_exists($className, self::$__singletonObjects)) {
			self::$__singletonObjects[$className] = null;
			self::$__singletonObjects[$className] = new $className();
		}
		else if (self::$__singletonObjects[$className] === null) {
			/** @noinspection PhpUnhandledExceptionInspection */
			throw new Exception("Infinite loop detected while trying to retrieve object for '".$className."'"); // TODO replace to SystemException
		}

		return self::$__singletonObjects[$className];
	}

	/**
	 * Returns whether this singleton is already initialized.
	 *
	 * @return	boolean
	 */
	public static final function isInitialized() {
		$className = get_called_class();

		return isset(self::$__singletonObjects[$className]);
	}
}
