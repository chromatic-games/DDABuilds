<?php

namespace data\steam\user;

use data\bugReport\BugReport;
use data\DatabaseObject;
use data\notification\NotificationList;

/**
 * represent a steam user
 * @package data\steam\user
 *
 * @property-read string $steamID
 * @property-read string $name
 * @property-read string $avatarHash
 */
class SteamUser extends DatabaseObject {
	/** @var int show the avatar in small size */
	const AVATAR_SMALL = 1;

	/** @var int show the avatar in medium size */
	const AVATAR_MEDIUM = 2;

	/** @var int show the avatar in big size */
	const AVATAR_BIG = 3;

	protected static $databaseTableIndexName = 'steamID';

	/**
	 * @var null|int|mixed
	 */
	protected $__unreadNotifications;

	/**
	 * get the avatar from steam user in given size
	 *
	 * @param int $size
	 *
	 * @return string
	 */
	public function getAvatar($size = self::AVATAR_MEDIUM) {
		$hashAdditional = '';
		if ( $size === self::AVATAR_BIG ) {
			$hashAdditional = '_full';
		}
		elseif ( $size === self::AVATAR_MEDIUM ) {
			$hashAdditional = '_medium';
		}

		return 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/'.substr($this->avatarHash, 0, 2).'/'.$this->avatarHash.$hashAdditional.'.jpg';
	}

	/**
	 * get the count of unread notifications from this user
	 *
	 * @return int
	 */
	public function getUnreadNotifications() {
		if ( $this->__unreadNotifications === null ) {
			$this->__unreadNotifications = 0;
			$notificationList = new NotificationList();
			$notificationList->getConditionBuilder()->add('steamID = ? AND seen = 0', [$this->steamID]);
			$this->__unreadNotifications = $notificationList->countObjects();
		}

		return $this->__unreadNotifications;
	}

	/**
	 * @return bool
	 */
	public function isMaintainer() {
		return in_array($this->steamID, BugReport::MAINTAINER);
	}
}