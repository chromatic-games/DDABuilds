<?php

namespace data\tower;

use data\DatabaseObject;

/**
 * @package data\tower
 *
 * @property-read int    $id
 * @property-read int    $mu
 * @property-read int    $unitcost
 * @property-read int    $manacost
 * @property-read int    $fk_class
 * @property-read string $name
 */
class Tower extends DatabaseObject {
	protected static $databaseTableName = 'towers';

	protected static $databaseTableIndexName = 'id';

	public function getImage() {
		return '/assets/images/tower/'.strtolower(str_replace(' ', '_', $this->name)).'.png';
	}
}