<?php

namespace system\cache\runtime;

use data\steam\user\SteamUser;
use data\steam\user\SteamUserList;

/**
 * Class SteamUserRuntimeCache
 * @package system\cache\runtime
 *
 * @method SteamUser[] getCachedObjects()
 * @method SteamUser[] getObjects(array $objectIDs)
 * @method SteamUser   getObject($objectID)
 */
class SteamUserRuntimeCache extends AbstractRuntimeCache {
	/** @inheritDoc */
	protected $listClassName = SteamUserList::class;
}