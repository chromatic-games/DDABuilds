<?php

namespace data\comment;

use data\DatabaseObjectList;
use system\cache\runtime\SteamUserRuntimeCache;
use system\Core;

class CommentList extends DatabaseObjectList {
	public function __construct() {
		parent::__construct();

		if ( Core::getUser()->steamID ) {
			$this->sqlSelects = 'like.likeValue as likeValue';
			$this->sqlJoins = 'LEFT JOIN `like` ON like.objectType = \'comment\' AND like.objectID = comments.id AND like.steamID = \''.Core::getUser()->steamID.'\'';
		}
	}

	public function readObjects() {
		parent::readObjects();

		$steamIDs = [];

		/** @var Comment $comment */
		foreach ( $this->getObjects() as $comment ) {
			if ( !in_array($comment->steamid, $steamIDs) ) {
				$steamIDs[] = $comment->steamid;
			}
		}

		SteamUserRuntimeCache::getInstance()->cacheObjectIDs($steamIDs);
	}
}