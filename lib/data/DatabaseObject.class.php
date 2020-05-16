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