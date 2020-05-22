<?php

namespace system;

use Exception;
use system\database\MySQLDatabase;
use system\exception\NamedUserException;
use system\exception\UserException;
use system\steam\SteamUser;
use system\template\TemplateEngine;

require_once(LIB_DIR.'core.functions.php');

class Core {
	/**
	 * @var MySQLDatabase
	 */
	protected static $dbObj;

	/**
	 * @var SteamUser
	 */
	protected static $userObj;

	protected static $tplObj;

	public static $tplVariables = [];

	public function __construct() {
		// do something on construct
	}

	/**
	 * @return SteamUser
	 */
	public static function getUser() {
		if ( self::$userObj === null ) {
			self::$userObj = new SteamUser(null, isset($_SESSION['_steam_profile']) ? $_SESSION['_steam_profile'] : []);
		}

		return self::$userObj;
	}

	public static function getTPL() {
		if ( self::$tplObj === null ) {
			self::$tplObj = new TemplateEngine();
		}

		return self::$tplObj;
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

	public static final function handleException($e) {
		if ( $e instanceof UserException ) {
			$e->show();
			exit;
		}

		// discard any output
		while ( ob_get_level() ) {
			ob_end_clean();
		}

		$exception = new NamedUserException($e->getMessage(), $e->getCode(), $e);
		$exception->show();
	}

	public static final function handleError($severity, $message, $file, $line) {
		// this is necessary for the shut-up operator
		if ( error_reporting() == 0 ) {
			return;
		}

		if ( !DEBUG_MODE ) {
			throw new NamedUserException('Error appeared'); // replace with systemexception
		}
		else {
			echo '<pre>';
			var_dump(implode(', ', [$severity, $message, $file, $line]));
			echo '</pre>';
		}
	}

	public static function destruct() {
		try {// database has to be initialized
			if ( !is_object(self::$dbObj) ) {
				return;
			}
		} catch ( Exception $e ) {
			// do nothing
		}
	}
}