<?php

namespace system;

use system\database\MySQLDatabase;

class Core {
	/**
	 * @var
	 */
	public static $dbObj;

	public function __construct() {
		// do something on construct
	}

	/**
	 * TODO
	 *
	 * @return null
	 */
	public static function getUser() {
		return null;
	}

	/**
	 * @return MySQLDatabase
	 */
	public static function getDB() {
		if ( self::$dbObj === null ) {
			self::$dbObj = new MySQLDatabase('localhost', 'root', '', 'ddabuilds', 3306); // TODO replace with config.php
		}

		return self::$dbObj;
	}

	public static function destruct() {
		try {// database has to be initialized
			if ( !is_object(self::$dbObj) ) {
				return;
			}
		} catch ( \Exception $e ) {
		}
	}
}