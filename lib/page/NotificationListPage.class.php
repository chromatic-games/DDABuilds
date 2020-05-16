<?php

namespace page;

use data\notification\NotificationList;
use system\Core;

class NotificationListPage extends SortablePage {
	public $loginRequired = true;

	public $objectListClassName = NotificationList::class;

	public $defaultSortField = 'date';

	public $defaultSortOrder = 'DESC';

	public function readParameters() {
		parent::readParameters();

		if ( Core::getUser()->getUnreadNotifications() ) {
			$statement = Core::getDB()->prepareStatement('UPDATE notifications SET seen = 1 WHERE steamID = ?');
			$statement->execute([Core::getUser()->steamID]);
		}
	}

	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('steamid = ?', [Core::getUser()->steamID]);
	}
}