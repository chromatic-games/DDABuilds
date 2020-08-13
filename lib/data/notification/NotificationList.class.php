<?php

namespace data\notification;

use data\DatabaseObjectList;
use system\cache\runtime\SteamUserRuntimeCache;

class NotificationList extends DatabaseObjectList {
	public function readObjects() {
		parent::readObjects();

		$steamIDs = [];

		/** @var Notification $notification */
		foreach ( $this->getObjects() as $notification ) {
			$steamID = null;
			if ( ($notification->type == 1 || $notification->type == 4) && !in_array($notification->data, $steamIDs) ) {
				$steamIDs[] = $notification->data;
			}
		}

		SteamUserRuntimeCache::getInstance()->cacheObjectIDs($steamIDs);
	}
}