<?php

namespace data\comment;

use data\DatabaseObject;

/**
 * @package data\comment
 *
 * @property-read integer $id
 * @property-read string  $steamid
 * @property-read string  $comment
 * @property-read integer $fk_build
 * @property-read integer $likes
 * @property-read integer $dislikes
 * @property-read string  $date
 * @property-read integer $likeValue
 */
class Comment extends DatabaseObject {
	const COMMENTS_PER_PAGE = 10;

	protected static $databaseTableName = 'comments';

	protected static $databaseTableIndexName = 'id';

	public function getDate() {
		return date('d F Y', strtotime($this->date));
	}
}