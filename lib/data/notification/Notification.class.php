<?php

namespace data\notification;

use data\DatabaseObject;

/**
 * @package data\notification
 *
 * @property-read int    $id
 * @property-read int    $steamid
 * @property-read int    $seen
 * @property-read int    $data
 * @property-read string $date
 * @property-read int    $type
 * @property-read int    $fk_build
 * @property-read int    $fk_comment
 */
class Notification extends DatabaseObject {
	protected static $databaseTableName = 'notifications';

	protected static $databaseTableIndexName = 'id';
}