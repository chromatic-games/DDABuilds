<?php

namespace system;

use system\database\MySQLDatabase;
use system\steam\SteamUser;

class Core {
	/**
	 * @var MySQLDatabase
	 */
	public static $dbObj;

	/**
	 * @var SteamUser
	 */
	public static $userObj;

	public function __construct() {
		// do something on construct
	}

	/**
	 * @return SteamUser
	 */
	public static function getUser() {
		if ( self::$userObj === null ) {
			self::$userObj = new SteamUser(null, isset($_SESSION['steam_profile']) ? $_SESSION['steam_profile'] : []);
		}

		return self::$userObj;
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