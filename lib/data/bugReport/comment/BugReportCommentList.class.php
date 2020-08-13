<?php

namespace data\bugReport\comment;

use data\DatabaseObjectList;
use system\cache\runtime\SteamUserRuntimeCache;

class BugReportCommentList extends DatabaseObjectList {
	/** @inheritDoc */
	public $className = BugReportComment::class;

	/** @inheritDoc */
	public function readObjects() {
		parent::readObjects();

		$steamIDs = [];
		/** @var BugReportComment $object */
		foreach ( $this->getObjects() as $object ) {
			$steamIDs[] = $object->steamID;
		}
		SteamUserRuntimeCache::getInstance()->cacheObjectIDs($steamIDs);
	}
}