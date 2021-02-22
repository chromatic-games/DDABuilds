<?php

namespace App\Observers;

use App\Models\Build\BuildComment;
use App\Notifications\BuildCommentNotification;

class BuildCommentObserver {
	public function created(BuildComment $buildComment) {
		$buildComment->build->increment('comments');

		// add notification to build owner, if the user is not the creator
		if ( $buildComment->build->user->ID !== $buildComment->steamID ) {
			$buildComment->build->user->notify(new BuildCommentNotification($buildComment));
		}
	}
}
