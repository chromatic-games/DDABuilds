<?php

namespace data\like\object;

use data\comment\Comment;
use data\notification\NotificationAction;
use system\Core;

/**
 * Class CommentLike
 * @package data\like\object
 *
 * @method Comment getObject()
 */
class CommentLike extends AbstractLike {
	protected static $baseClass = Comment::class;

	protected static $enabledDislikes = true;

	/** @inheritDoc */
	public function canLike($steamID) {
		return $this->getObject()->steamid !== Core::getUser()->steamID;
	}

	public function createNotification($recipient, $steamID, $likeValue) {
		$notificationAction = new NotificationAction([], 'create', [
			'data' => [
				'steamid'    => $recipient,
				'data'       => $likeValue,
				'date'       => date('Y-m-d H:i:s'),
				'type'       => 3,
				'fk_build'   => $this->getObject()->fk_build,
				'fk_comment' => $this->getObjectID(),
			],
		]);
		$notificationAction->executeAction();
	}

	public function deleteNotification($recipient, $steamID) {
		$statement = Core::getDB()->prepareStatement('DELETE FROM `notifications` WHERE steamid = ? AND type = 3 AND fk_build = ? AND fk_comment = ?');
		$statement->execute([
			$recipient,
			$this->getObject()->fk_build,
			$this->getObjectID(),
		]);
	}

	public function updateNotification($recipient, $steamID, $newValue) {
		$statement = Core::getDB()->prepareStatement('UPDATE `notifications` SET data = ?, seen = 0, date = CURRENT_TIMESTAMP WHERE steamid = ? AND type = 3 AND fk_comment = ? AND fk_build = ?');
		$statement->execute([
			$newValue,
			$recipient,
			$this->getObjectID(),
			$this->getObject()->fk_build,
		]);

		return $statement->getAffectedRows();
	}
}