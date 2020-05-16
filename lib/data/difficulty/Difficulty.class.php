<?php

namespace data\difficulty;

use data\DatabaseObject;

/**
 * @package data\difficulty
 *
 * @property-read integer $id
 * @property-read string  $name
 */
class Difficulty extends DatabaseObject {
	protected static $databaseTableName = 'difficulties';

	protected static $databaseTableIndexName = 'id';
}