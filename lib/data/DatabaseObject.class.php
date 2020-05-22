<?php

namespace data;

use Exception;
use system\Core;

class DatabaseObject {
	/**
	 * database table for this object
	 * @var    string
	 */
	protected static $databaseTableName = '';

	/**
	 * indicates if database table index is an identity column
	 * @var    boolean
	 */
	protected static $databaseTableIndexIsIdentity = true;

	/**
	 * name of the primary index column
	 * @var    string
	 */
	protected static $databaseTableIndexName = '';

	/**
	 * object data
	 * @var    array
	 */
	protected $data;

	/**
	 * Creates a new instance of the DatabaseObject class.
	 *
	 * @param mixed          $id
	 * @param array          $row
	 * @param DatabaseObject $object
	 *
	 * @throws Exception
	 */
	public function __construct($id, array $row = null, DatabaseObject $object = null) {
		if ( $id !== null ) {
			$sql = "SELECT	*
				FROM	".static::getDatabaseTableName()."
				WHERE	".static::getDatabaseTableIndexName()." = ?";
			$statement = Core::getDB()->prepareStatement($sql);
			$statement->execute([$id]);
			$row = $statement->fetchArray();

			// enforce data type 'array'
			if ( $row === false ) {
				$row = [];
			}
		}
		elseif ( $object !== null ) {
			$row = $object->data;
		}

		$this->handleData($row);
	}

	/**
	 * Creates a new object.
	 *
	 * @param array $parameters
	 *
	 * @return DatabaseObject
	 * @throws Exception
	 */
	public static function create(array $parameters = []) {
		$keys = $values = '';
		$statementParameters = [];
		foreach ( $parameters as $key => $value ) {
			if ( !empty($keys) ) {
				$keys .= ',';
				$values .= ',';
			}

			$keys .= $key;
			$values .= '?';
			$statementParameters[] = $value;
		}

		// save object
		$sql = "INSERT INTO	".static::getDatabaseTableName()."
					(".$keys.")
			VALUES		(".$values.")";
		$statement = Core::getDB()->prepareStatement($sql);
		$statement->execute($statementParameters);

		// return new object
		if ( static::getDatabaseTableIndexIsIdentity() ) {
			$id = Core::getDB()->getInsertID();
		}
		else {
			$id = $parameters[static::getDatabaseTableIndexName()];
		}

		return new static($id);
	}

	/**
	 * @param array $parameters
	 *
	 * @throws Exception
	 */
	public function update(array $parameters) {
		if ( empty($parameters) ) {
			return;
		}

		$updateSQL = '';
		$statementParameters = [];
		foreach ( $parameters as $key => $value ) {
			if ( !empty($updateSQL) ) {
				$updateSQL .= ', ';
			}

			$updateSQL .= $key.' = ?';
			$statementParameters[] = $value;
		}
		$statementParameters[] = $this->getObjectID();

		$sql = "UPDATE	".static::getDatabaseTableName()."
			SET	".$updateSQL."
			WHERE	".static::getDatabaseTableIndexName()." = ?";
		$statement = Core::getDB()->prepareStatement($sql);
		$statement->execute($statementParameters);
	}

	/**
	 * Updates the counters of this object.
	 *
	 * @param array $counters
	 *
	 * @throws Exception
	 */
	public function updateCounters(array $counters = []) {
		if ( empty($counters) ) {
			return;
		}

		$updateSQL = '';
		$statementParameters = [];
		foreach ( $counters as $key => $value ) {
			if ( !empty($updateSQL) ) {
				$updateSQL .= ', ';
			}
			$updateSQL .= $key.' = '.$key.' + ?';
			$statementParameters[] = $value;
		}
		$statementParameters[] = $this->getObjectID();

		$sql = "UPDATE	".static::getDatabaseTableName()."
			SET	".$updateSQL."
			WHERE	".static::getDatabaseTableIndexName()." = ?";
		$statement = Core::getDB()->prepareStatement($sql);
		$statement->execute($statementParameters);
	}

	/**
	 * Deletes this object.
	 */
	public function delete() {
		static::deleteAll([$this->getObjectID()]);
	}

	/**
	 * Deletes all objects with the given ids and returns the number of deleted objects.
	 */
	public static function deleteAll(array $objectIDs = []) {
		$sql = "DELETE FROM	".static::getDatabaseTableName()."
			WHERE		".static::getDatabaseTableIndexName()." = ?";
		$statement = Core::getDB()->prepareStatement($sql);

		$affectedCount = 0;
		Core::getDB()->beginTransaction();
		foreach ( $objectIDs as $objectID ) {
			$statement->execute([$objectID]);
			$affectedCount += $statement->getAffectedRows();
		}
		Core::getDB()->commitTransaction();

		return $affectedCount;
	}

	/**
	 * Stores the data of a database row.
	 *
	 * @param array $data
	 */
	protected function handleData($data) {
		// provide a logical false value for - assumed numeric - primary index
		if ( !isset($data[static::getDatabaseTableIndexName()]) ) {
			$data[static::getDatabaseTableIndexName()] = 0;
		}

		$this->data = $data;
	}

	/**
	 * Returns the id of the object.
	 *
	 * @return    integer
	 */
	public function getObjectID() {
		return $this->data[static::getDatabaseTableIndexName()];
	}

	/**
	 * Returns true if database table index is an identity column.
	 *
	 * @return    boolean
	 */
	public static function getDatabaseTableIndexIsIdentity() {
		return static::$databaseTableIndexIsIdentity;
	}

	/**
	 * Returns the name of the database table.
	 *
	 * @return    string
	 */
	public static function getDatabaseTableName() {
		$className = get_called_class();
		$classParts = explode('\\', $className);

		if ( static::$databaseTableName !== '' ) {
			return static::$databaseTableName;
		}

		static $databaseTableNames = [];
		if ( !isset($databaseTableNames[$className]) ) {
			$databaseTableNames[$className] = strtolower(implode('_', preg_split('~(?=[A-Z](?=[a-z]))~', array_pop($classParts), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)));
		}

		return $databaseTableNames[$className];
	}

	/**
	 * Returns the alias of the database table.
	 *
	 * @return    string
	 */
	public static function getDatabaseTableAlias() {
		if ( static::$databaseTableName !== '' ) {
			return static::$databaseTableName;
		}

		$className = get_called_class();
		static $databaseTableAliases = [];
		if ( !isset($databaseTableAliases[$className]) ) {
			$classParts = explode('\\', $className);
			$databaseTableAliases[$className] = strtolower(implode('_', preg_split('~(?=[A-Z](?=[a-z]))~', array_pop($classParts), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)));
		}

		return $databaseTableAliases[$className];
	}

	/**
	 * Returns the name of the database table index.
	 *
	 * @return    string
	 */
	public static function getDatabaseTableIndexName() {
		if ( static::$databaseTableIndexName !== '' ) {
			return static::$databaseTableIndexName;
		}

		static $databaseTableIndexName = null;
		if ( $databaseTableIndexName === null ) {
			$className = explode('\\', get_called_class());
			$parts = preg_split('~(?=[A-Z](?=[a-z]))~', array_pop($className), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
			$databaseTableIndexName = strtolower(array_pop($parts)).'ID';
		}

		return $databaseTableIndexName;
	}

	/**
	 * Returns the value of a object data variable with the given name or `null` if no
	 * such data variable exists.
	 *
	 * @param string $name
	 *
	 * @return    mixed
	 */
	public function __get($name) {
		if ( isset($this->data[$name]) ) {
			return $this->data[$name];
		}
		else {
			return null;
		}
	}
}