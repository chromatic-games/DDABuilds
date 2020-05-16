<?php

namespace data\map;

use data\DatabaseObject;

/**
 * @package data\map
 *
 * @property-read int    $id
 * @property-read string $name
 * @property-read int    $units
 * @property-read int    $sort
 * @property-read int    $fk_mapcategory
 */
class Map extends DatabaseObject {
	protected static $databaseTableName = 'maps';

	protected static $databaseTableIndexName = 'id';
}