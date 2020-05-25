<?php

namespace data\map;

use data\DatabaseObject;
use data\IRouteObject;
use Exception;
use system\Core;

/**
 * @package data\map
 *
 * @property-read int    $id
 * @property-read string $name
 * @property-read int    $units
 * @property-read int    $sort
 * @property-read int    $fk_mapcategory
 */
class Map extends DatabaseObject implements IRouteObject {
	protected static $databaseTableName = 'maps';

	protected static $databaseTableIndexName = 'id';

	/**
	 * get a map by name
	 *
	 * @param string $name
	 *
	 * @return Map
	 * @throws Exception
	 */
	public static function getByName($name) {
		$name = str_replace([' ', '_'], '', $name);
		$statement = Core::getDB()->prepareStatement("SELECT * FROM ".static::getDatabaseTableName()." WHERE REPLACE(name, ' ', '') = ?");
		$statement->execute([$name]);
		$result = $statement->fetchArray();

		return new Map(null, $result ? $result : []);
	}

	/**
	 * get background image of map
	 *
	 * @return string
	 */
	public function getImage() {
		$name = str_replace(' ', '_', $this->name);

		return '/assets/images/map/'.$name.'.png';
	}

	public function getTitle() {
		return $this->name;
	}
}