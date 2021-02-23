<?php

namespace App\Models\Like;

use App\Models\Build;

/**
 * @method Build getObject()
 */
class BuildLike extends AbstractLike {
	protected static $baseClass = Build::class;

	public function getNotificationData() : array {
		return [
			'build' => $this->getObject()->getNotificationData(),
		];
	}
}