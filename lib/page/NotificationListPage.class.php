<?php

namespace page;

use data\notification\NotificationList;
use system\Core;

class NotificationListPage extends SortablePage {
	/** @inheritDoc */
	public $loginRequired = true;

	/** @inheritDoc */
	public $objectListClassName = NotificationList::class;

	/** @inheritDoc */
	public $defaultSortField = 'date';

	/** @inheritDoc */
	public $defaultSortOrder = 'DESC';

	/** @inheritDoc */
	public $pageTitle = 'Notifications';

	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( Core::getUser()->getUnreadNotifications() ) {
			$statement = Core::getDB()->prepareStatement('UPDATE notifications SET seen = 1 WHERE steamID = ?');
			$statement->execute([Core::getUser()->steamID]);
		}
	}

	/** @inheritDoc */
	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('steamid = ?', [Core::getUser()->steamID]);
	}
}