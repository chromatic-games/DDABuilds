<?php

namespace data\map\category;

use data\DatabaseObject;

/**
 * Class MapCategory
 * @package data\map\category
 *
 * @property-read int    $id
 * @property-read string $name
 * @property-read string $text
 */
class MapCategory extends DatabaseObject {
	protected static $databaseTableName = 'mapcategories';

	protected static $databaseTableIndexName = 'id';
}