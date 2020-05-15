<?php
namespace system\database;
// use system\database\exception\DatabaseException;
// use system\database\exception\DatabaseQueryException;
// use system\database\exception\DatabaseTransactionException;
use Exception;
use PDO;
use PDOException;
use system\database\statement\PreparedStatement;

/**
 * Abstract implementation of a database access class using PDO.
 */
abstract class Database {
	/**
	 * name of the class used for prepared statements
	 * @var	string
	 */
	protected $preparedStatementClassName = PreparedStatement::class;

	/**
	 * sql server hostname
	 * @var	string
	 */
	protected $host = '';

	/**
	 * sql server post
	 * @var	integer
	 */
	protected $port = 0;

	/**
	 * sql server login name
	 * @var	string
	 */
	protected $user = '';

	/**
	 * sql server login password
	 * @var	string
	 */
	protected $password = '';

	/**
	 * database name
	 * @var	string
	 */
	protected $database = '';

	/**
	 * enables failsafe connection
	 * @var	boolean
	 */
	protected $failsafeTest = false;

	/**
	 * number of executed queries
	 * @var	integer
	 */
	protected $queryCount = 0;

	/**
	 * pdo object
	 * @var	PDO
	 */
	protected $pdo = null;

	/**
	 * amount of active transactions
	 * @var	integer
	 */
	protected $activeTransactions = 0;

	/**
	 * attempts to create the database after the connection has been established
	 * @var boolean
	 */
	protected $tryToCreateDatabase = false;

	/**
	 * Creates a Database Object.
	 *
	 * @param	string		$host			SQL database server host address
	 * @param	string		$user			SQL database server username
	 * @param	string		$password		SQL database server password
	 * @param	string		$database		SQL database server database name
	 * @param	integer		$port			SQL database server port
	 * @param	boolean		$failsafeTest
	 * @param       boolean         $tryToCreateDatabase
	 */
	public function __construct($host, $user, $password, $database, $port, $failsafeTest = false, $tryToCreateDatabase = false) {
		$this->host = $host;
		$this->port = $port;
		$this->user = $user;
		$this->password = $password;
		$this->database = $database;
		$this->failsafeTest = $failsafeTest;
		$this->tryToCreateDatabase = $tryToCreateDatabase;

		// connect database
		$this->connect();
	}

	/**
	 * Connects to database server.
	 */
	abstract public function connect();

	/**
	 * Returns ID from last insert.
	 *
	 * @return	integer
	 * @throws	Exception
	 */
	public function getInsertID() {
		try {
			return $this->pdo->lastInsertId();
		}
		catch ( PDOException $e) {
			throw new Exception("Cannot fetch last insert id"); // TODO DatabaseException
		}
	}

	/**
	 * Initiates a transaction.
	 *
	 * @return	boolean		true on success
	 * @throws	Exception
	 */
	public function beginTransaction() {
		try {
			if ($this->activeTransactions === 0) {
				$result = $this->pdo->beginTransaction();
			}
			else {
				$result = $this->pdo->exec("SAVEPOINT level".$this->activeTransactions) !== false;
			}

			$this->activeTransactions++;

			return $result;
		}
		catch ( PDOException $e) {
			throw new Exception("Could not begin transaction"); // TODO DatabaseTransactionException
		}
	}

	/**
	 * Commits a transaction and returns true if the transaction was successful.
	 *
	 * @return	boolean
	 * @throws	Exception
	 */
	public function commitTransaction() {
		if ($this->activeTransactions === 0) return false;

		try {
			$this->activeTransactions--;

			if ($this->activeTransactions === 0) {
				$result = $this->pdo->commit();
			}
			else {
				$result = $this->pdo->exec("RELEASE SAVEPOINT level".$this->activeTransactions) !== false;
			}

			return $result;
		}
		catch ( PDOException $e) {
			throw new Exception("Could not commit transaction"); // TODO replace with own database exception? (DatabaseTransactionException)
		}
	}

	/**
	 * Rolls back a transaction and returns true if the rollback was successful.
	 *
	 * @return	boolean
	 * @throws	Exception
	 */
	public function rollBackTransaction() {
		if ($this->activeTransactions === 0) return false;

		try {
			$this->activeTransactions--;
			if ($this->activeTransactions === 0) {
				$result = $this->pdo->rollBack();
			}
			else {
				$result = $this->pdo->exec("ROLLBACK TO SAVEPOINT level".$this->activeTransactions) !== false;
			}

			return $result;
		}
		catch ( PDOException $e) {
			throw new Exception("Could not roll back transaction"); // TODO replace with own database exception? (DatabaseTransactionException)
		}
	}

	/**
	 * Prepares a statement for execution and returns a statement object.
	 *
	 * @param	string			$statement
	 * @param	integer			$limit
	 * @param	integer			$offset
	 * @return	PreparedStatement
	 * @throws	Exception
	 */
	public function prepareStatement($statement, $limit = 0, $offset = 0) {
		$statement = $this->handleLimitParameter($statement, $limit, $offset);

		try {
			$pdoStatement = $this->pdo->prepare($statement);

			return new $this->preparedStatementClassName($this, $pdoStatement, $statement);
		}
		catch ( PDOException $e) {
			echo '<pre>';
			var_dump($e);
			throw new Exception("Could not prepare statement '".$statement."'"); // TODO replace with own database exception? (DatabaseQueryException)
		}
	}

	/**
	 * Handles the limit and offset parameter in SELECT queries.
	 * This is a default implementation compatible to MySQL and PostgreSQL.
	 * Other database implementations should override this function.
	 *
	 * @param	string		$query
	 * @param	integer		$limit
	 * @param	integer		$offset
	 * @return	string
	 */
	public function handleLimitParameter($query, $limit = 0, $offset = 0) {
		if ($limit != 0) {
			$query = preg_replace('~(\s+FOR\s+UPDATE\s*)?$~', " LIMIT " . $limit . ($offset ? " OFFSET " . $offset : '') . "\\0", $query, 1);
		}

		return $query;
	}

	/**
	 * Returns the number of the last error.
	 *
	 * @return	integer
	 */
	public function getErrorNumber() {
		if ($this->pdo !== null) return $this->pdo->errorCode();
		return 0;
	}

	/**
	 * Returns the description of the last error.
	 *
	 * @return	string
	 */
	public function getErrorDesc() {
		if ($this->pdo !== null) {
			$errorInfoArray = $this->pdo->errorInfo();
			if (isset($errorInfoArray[2])) return $errorInfoArray[2];
		}
		return '';
	}

	/**
	 * Escapes a string for use in sql query.
	 *
	 * @param	string		$string
	 * @return	string
	 */
	public function escapeString($string) {
		return addslashes($string);
	}

	/**
	 * Returns the database name.
	 *
	 * @return	string
	 */
	public function getDatabaseName() {
		return $this->database;
	}

	/**
	 * Returns the name of the database user.
	 *
	 * @return	string		user name
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * Returns the amount of executed sql queries.
	 *
	 * @return	integer
	 */
	public function getQueryCount() {
		return $this->queryCount;
	}

	/**
	 * Increments the query counter by one.
	 */
	public function incrementQueryCount() {
		$this->queryCount++;
	}

	/**
	 * Sets default connection attributes.
	 */
	protected function setAttributes() {
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
		$this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
	}
}
