<?php

namespace data\difficulty;

use data\DatabaseObject;
use Exception;
use system\Core;

/**
 * @package data\difficulty
 *
 * @property-read integer $id
 * @property-read string  $name
 */
class Difficulty extends DatabaseObject {
	protected static $databaseTableName = 'difficulties';

	protected static $databaseTableIndexName = 'id';

	/**
	 * get a difficulty by name
	 *
	 * @param string $name
	 *
	 * @return Difficulty
	 * @throws Exception
	 */
	public static function getByName($name) {
		$name = str_replace([' ', '_'], '', $name);
		$statement = Core::getDB()->prepareStatement("SELECT * FROM ".static::getDatabaseTableName()." WHERE REPLACE(name, ' ', '') = ?");
		$statement->execute([$name]);
		$result = $statement->fetchArray();

		return new Difficulty(null, $result ? $result : []);
	}
}