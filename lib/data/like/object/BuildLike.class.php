<?php

namespace data\like\object;

use data\build\Build;

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
}