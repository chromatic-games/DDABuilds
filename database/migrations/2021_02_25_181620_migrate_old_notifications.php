<?php

use App\Models\Build;
use App\Models\DatabaseNotification;
use App\Models\Like;
use App\Models\SteamUser;
use App\Notifications\BuildCommentNotification;
use App\Notifications\LikeNotification;
use Illuminate\Database\Migrations\Migration;

class MigrateOldNotifications extends Migration {
	public function up() {
		// add build comment notifications
		$buildComments = Build\BuildComment::query()->get()->all();
		/** @var Build\BuildComment $buildComment */
		foreach ( $buildComments as $buildComment ) {
			if ( $buildComment->steamID !== $buildComment->build->steamID ) {
				$buildComment->build->user->notify(new BuildCommentNotification($buildComment));
			}
		}

		$likes = Like::query()->get()->all();
		/** @var Like $likeObject */
		foreach ( $likes as $likeObject ) {
			$className = 'App\\Models\\Like\\'.ucfirst($likeObject->objectType).'Like';
			$like = new $className($likeObject->objectID, auth()->id());

			/** @var SteamUser $user */
			$user = SteamUser::query()->find($like->getRecipientID());
			$user->notify(new LikeNotification($like, $likeObject));
		}

		// mark all as read
		DatabaseNotification::query()->update(['read_at' => now()]);
	}

	public function down() {
		// cant be undo
	}
}
