<?php

namespace system\cache\runtime;

use data\gamemode\Gamemode;
use data\gamemode\GamemodeList;

/**
 * represent the runtime cache for gamemodes
 *
 * @method Gamemode[] getCachedObjects()
 * @method Gamemode[] getObjects(array $objectIDs)
 * @method Gamemode   getObject($objectID)
 */
class GamemodeRuntimeCache extends AbstractRuntimeCache {
	/** @inheritDoc */
	protected $listClassName = GamemodeList::class;
}