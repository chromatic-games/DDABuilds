<?php

namespace data\gamemode;

use data\DatabaseObject;
use Exception;
use system\Core;

/**
 * represent a game mode
 *
 * @property-read integer $gamemodeID
 * @property-read string  $name
 */
class Gamemode extends DatabaseObject {
	/**
	 * get a game mode by name
	 *
	 * @param string $name
	 *
	 * @return Gamemode
	 * @throws Exception
	 */
	public static function getByName($name) {
		$name = str_replace([' ', '_'], '', $name);
		$statement = Core::getDB()->prepareStatement("SELECT * FROM ".static::getDatabaseTableName()." WHERE REPLACE(name, ' ', '') = ?");
		$statement->execute([$name]);
		$result = $statement->fetchArray();

		return new Gamemode(null, $result ? $result : []);
	}
}