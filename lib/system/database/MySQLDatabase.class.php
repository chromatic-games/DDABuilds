<?php

namespace system\database;

/**
 * This is the database implementation for MySQL 5.1 or higher using PDO.
 */
class MySQLDatabase extends Database {
	/**
	 * @inheritDoc
	 */
	public function connect() {
		if ( !$this->port ) {
			$this->port = 3306;
		} // mysql default port

		try {
			$driverOptions = [
				\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4'",
			];
			if ( !$this->failsafeTest ) {
				$driverOptions = [
					\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4', SESSION sql_mode = 'ANSI,STRICT_ALL_TABLES'", // TODO re-add ONLY_FULL_GROUP_BY
				];
			}

			// disable prepared statement emulation since MySQL 5.1.17 is the minimum required version
			$driverOptions[\PDO::ATTR_EMULATE_PREPARES] = false;

			// throw PDOException instead of dumb false return values
			$driverOptions[\PDO::ATTR_ERRMODE] = \PDO::ERRMODE_EXCEPTION;

			$dsn = 'mysql:host='.$this->host.';port='.$this->port;
			if ( !$this->tryToCreateDatabase ) {
				$dsn .= ';dbname='.$this->database;
			}

			$this->pdo = new \PDO($dsn, $this->user, $this->password, $driverOptions);
			$this->setAttributes();

			if ( $this->tryToCreateDatabase ) {
				try {
					$this->pdo->exec("USE ".$this->database);
				} catch ( \PDOException $e ) {
					// 1049 = Unknown database
					if ( $this->pdo->errorInfo()[1] == 1049 ) {
						$this->pdo->exec("CREATE DATABASE ".$this->database);
						$this->pdo->exec("USE ".$this->database);
					}
					else {
						throw $e;
					}
				}
			}
		} catch ( \PDOException $e ) {
			throw new \Exception("Connecting to MySQL server '".$this->host."' failed"); // TODO DatabaseException
		}
	}

	/**
	 * @inheritDoc
	 */
	protected function setAttributes() {
		parent::setAttributes();
		$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
	}
}
