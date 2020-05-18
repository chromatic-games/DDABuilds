<?php

namespace data\like\object;

use data\comment\Comment;
use system\Core;

/**
 * Class CommentLike
 * @package data\like\object
 *
 * @method Comment getObject()
 */
class CommentLike extends AbstractLike {
	protected static $baseClass = Comment::class;

	protected static $enabledDislikes = true;

	/** @inheritDoc */
	public function canLike($steamID) {
		return $this->getObject()->steamid !== Core::getUser()->steamID;
	}
}