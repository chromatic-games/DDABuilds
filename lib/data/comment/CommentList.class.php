<?php

namespace data\comment;

use data\DatabaseObjectList;
use system\Core;

class CommentList extends DatabaseObjectList {
	public function __construct() {
		parent::__construct();

		if ( Core::getUser()->steamID ) {
			$this->sqlSelects = 'like.likeValue as likeValue';
			$this->sqlJoins = 'LEFT JOIN `like` ON like.objectType = \'comment\' AND like.objectID = comments.id AND like.steamID = \''.Core::getUser()->steamID.'\'';
		}
	}
}