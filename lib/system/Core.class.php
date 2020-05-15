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
			$dbPort = 3306;
			/**
			 * variables from mysql.inc.php:
			 *
			 * @var string $dbPassword
			 * @var string $dbHost
			 * @var string $dbUser
			 * @var string $dbName
			 */
			require_once(MAIN_DIR.'mysql.inc.php');
			self::$dbObj = new MySQLDatabase($dbHost, $dbUser, $dbPassword, $dbName, $dbPort);
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