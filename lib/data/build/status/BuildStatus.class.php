<?php

namespace data\build\status;

use data\DatabaseObject;

/**
 * @package data\build\status
 *
 * @property-read integer $id
 * @property-read string $name
 */
class BuildStatus extends DatabaseObject {
	protected static $databaseTableName = 'buildstatuses';
	protected static $databaseTableIndexName = 'id';
}