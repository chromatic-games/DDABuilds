<?php

namespace data\build\wave;

use data\DatabaseObject;

/**
 * @package data\build\wave
 *
 * @property-read integer $fk_build
 * @property-read string $name
 */
class BuildWave extends DatabaseObject {
	protected static $databaseTableName = 'buildwaves';

	protected static $databaseTableIndexName = 'id';
}