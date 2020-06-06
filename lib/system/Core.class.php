<?php

namespace system;

use data\steam\user\SteamUser;
use Exception;
use system\cache\runtime\SteamUserRuntimeCache;
use system\database\MySQLDatabase;
use system\exception\NamedUserException;
use system\template\TemplateEngine;
use function functions\exception\logThrowable;
use function functions\exception\printThrowable;

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
			$steamID = null;
			if ( isset($_SESSION['__steamid']) ) {
				$steamID = $_SESSION['__steamid'];
			}

			self::$userObj = $steamID !== null ? SteamUserRuntimeCache::getInstance()->getObject($steamID) : new SteamUser(null, []);
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
		// discard any output
		while ( ob_get_level() ) {
			ob_end_clean();
		}

		if ( $e instanceof NamedUserException ) {
			$e->show();
			exit;
		}

		// print all other errors
		printThrowable($e);
	}

	public static final function handleError($severity, $message, $file, $line) {
		// this is necessary for the shut-up operator
		if ( error_reporting() == 0 ) {
			return;
		}

		logThrowable(new \Exception($message, 0));
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