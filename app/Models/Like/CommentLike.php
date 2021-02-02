<?php

namespace App\Models\Like;

use App\Models\Build\BuildComment;

class CommentLike extends AbstractLike {
	protected static $enabledDislikes = true;

	protected static $baseClass = BuildComment::class;
}