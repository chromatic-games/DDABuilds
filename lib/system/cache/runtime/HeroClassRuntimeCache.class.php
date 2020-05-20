<?php

namespace system\cache\runtime;

use data\heroClass\HeroClass;
use data\heroClass\HeroClassList;

/**
 * Class HeroClassRuntimeCache
 * @package system\cache\runtime
 *
 * @method HeroClass[] getCachedObjects()
 * @method HeroClass[] getObjects(array $objectIDs)
 * @method HeroClass   getObject($objectID)
 */
class HeroClassRuntimeCache extends AbstractRuntimeCache {
	protected $listClassName = HeroClassList::class;
}