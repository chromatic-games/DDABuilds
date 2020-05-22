<?php

namespace system\cache\runtime;

use data\build\status\BuildStatus;
use data\build\status\BuildStatusList;

/**
 * Class BuildStatusRuntimeCache
 * @package system\cache\runtime
 *
 * @method BuildStatus[] getCachedObjects()
 * @method BuildStatus[] getObjects(array $objectIDs)
 * @method BuildStatus   getObject($objectID)
 */
class BuildStatusRuntimeCache extends AbstractRuntimeCache {
	protected $listClassName = BuildStatusList::class;
}