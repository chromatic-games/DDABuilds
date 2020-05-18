<?php

namespace system\cache\runtime;

use data\map\Map;
use data\map\MapList;

/**
 * Class MapRuntimeCache
 * @package system\cache\runtime
 *
 * @method Map[] getCachedObjects()
 * @method Map[] getObjects(array $objectIDs)
 * @method Map   getObject($objectID)
 */
class MapRuntimeCache extends AbstractRuntimeCache {
	protected $listClassName = MapList::class;
}