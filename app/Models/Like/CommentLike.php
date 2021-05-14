<?php

namespace App\Models\Like;

use App\Models\Build\BuildComment;

/**
 * @method BuildComment getObject()
 */
class CommentLike extends AbstractLike {
	protected static $enabledDislikes = true;

	protected static $baseClass = BuildComment::class;

	public function getNotificationData() : array {
		return [
			'build' => $this->getObject()->build->getNotificationData(),
		];
	}
}