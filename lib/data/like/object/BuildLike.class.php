<?php

namespace data\like\object;

use data\build\Build;
use data\notification\NotificationAction;
use system\Core;

/**
 * Class BuildLike
 * @package data\like\object
 *
 * @method Build getObject()
 */
class BuildLike extends AbstractLike {
	protected static $baseClass = Build::class;

	public function canLike($steamID) {
		return $this->getObject()->fk_user !== $steamID;
	}

	public function createNotification($recipient, $steamID, $likeValue) {
		$notificationAction = new NotificationAction([], 'create', [
			'data' => [
				'steamid'    => $recipient,
				'data'       => $likeValue,
				'date'       => date('Y-m-d H:i:s'),
				'type'       => $this->getObjectType() === 'build' ? 2 : 3,
				'fk_build'   => $this->getObjectID(),
				'fk_comment' => null,
			],
		]);
		$notificationAction->executeAction();
	}

	public function updateNotification($recipient, $steamID, $newValue) {
		$statement = Core::getDB()->prepareStatement('UPDATE `notifications` SET data = ?, seen = 0, date = CURRENT_TIMESTAMP WHERE steamid = ? AND type = 2 AND fk_build = ? AND fk_comment IS NULL');
		$statement->execute([
			$newValue,
			$recipient,
			$this->getObjectID(),
		]);

		return $statement->getAffectedRows();
	}

	public function deleteNotification($recipient, $steamID) {
		$statement = Core::getDB()->prepareStatement('DELETE FROM `notifications` WHERE steamid = ? AND type = 2 AND fk_build = ? AND fk_comment IS NULL');
		$statement->execute([
			$recipient,
			$this->getObjectID(),
		]);
	}
}