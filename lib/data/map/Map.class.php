<?php

namespace data\map;

use data\DatabaseObject;
use data\IRouteObject;

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

	public function getImage() {
		$name = str_replace(' ', '_', $this->name);

		return '/assets/images/map/'.$name.'.png';
	}

	public function getTitle() {
		return $this->name;
	}
}