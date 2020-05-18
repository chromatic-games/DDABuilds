<?php

namespace system\cache\runtime;

use data\difficulty\Difficulty;
use data\difficulty\DifficultyList;

/**
 * Class DifficultyRuntimeCache
 * @package system\cache\runtime
 *
 * @method Difficulty[] getCachedObjects()
 * @method Difficulty[] getObjects(array $objectIDs)
 * @method Difficulty   getObject($objectID)
 */
class DifficultyRuntimeCache extends AbstractRuntimeCache {
	protected $listClassName = DifficultyList::class;
}