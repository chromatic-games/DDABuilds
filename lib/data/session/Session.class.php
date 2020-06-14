<?php

namespace data\session;

use data\DatabaseObject;
use system\Core;

/**
 * represent a login session
 *
 * @property-read string sessionID
 * @property-read string steamID
 * @property-read int    expires
 */
class Session extends DatabaseObject {
	/** @inheritDoc */
	protected static $databaseTableIndexIsIdentity = false;

	/**
	 * @return bool
	 */
	public function isExpired() {
		return $this->expires < TIME_NOW;
	}

	/**
	 * @param string $steamID
	 */
	public static function getSessionBySteamID($steamID) {
		$statement = Core::getDB()->prepareStatement('SELECT * FROM session WHERE steamID = ?');
		$statement->execute([$steamID]);

		$data = $statement->fetch(\PDO::FETCH_ASSOC);
		if ( !$data ) {
			$data = [];
		}

		return new Session(null, $data);
	}
}