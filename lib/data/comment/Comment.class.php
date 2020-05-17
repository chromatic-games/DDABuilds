<?php

namespace data\comment;

use data\DatabaseObject;

/**
 * @package data\comment
 *
 * @property-read integer $id
 * @property-read string $steamid
 * @property-read string $comment
 * @property-read integer fk_build
 * @property-read string $date
 */
class Comment extends DatabaseObject {
	protected static $databaseTableName = 'comments';

	protected static $databaseTableIndexName = 'id';

	public function getDate() {
		return date('d F Y', strtotime($this->date));
	}
}