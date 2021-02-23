<?php

namespace App\Http\Controllers;

use App\Models\DatabaseNotification;
use App\Models\Like;
use App\Models\Like\AbstractLike;
use App\Models\SteamUser;
use App\Notifications\LikeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LikeController extends AbstractController {
	public function like(Request $request) : array {
		$objectID = $request->get('objectID');
		$objectType = $request->get('objectType');
		$likeType = $request->get('likeType');

		if ( !$objectID || !$objectType || !$likeType ) {
			throw new BadRequestHttpException();
		}

		$className = 'App\\Models\\Like\\'.ucfirst($objectType).'Like';
		if ( !class_exists($className) ) {
			throw new BadRequestHttpException();
		}
		elseif ( !is_subclass_of($className, AbstractLike::class) ) {
			throw new BadRequestHttpException(sprintf("Class '%s' does not extend class '%s'.", $className, AbstractLike::class));
		}

		/** @var AbstractLike $like */
		$like = new $className($objectID, auth()->id());
		$this->authorize('like', $like->getObject());

		if ( $likeType === 'dislike' && !$like->isEnabledDislikes() ) {
			throw new BadRequestHttpException('Dislike is disabled.');
		}

		$likeObject = $like->getLikeObject();
		$oldLikeValue = $like->getLikeValue();
		$newLikeValue = $likeType === 'like' ? AbstractLike::LIKE : AbstractLike::DISLIKE;
		$oldCounter = $oldLikeValue === AbstractLike::LIKE ? 'likes' : 'dislikes';
		$newCounter = $newLikeValue === AbstractLike::LIKE ? 'likes' : 'dislikes';
		$newState = [];

		if ( $oldLikeValue === null ) {
			$likeObject = Like::query()->create([
				'objectType' => $like->getObjectType(),
				'objectID' => $like->getObjectID(),
				'steamID' => auth()->id(),
				'likeValue' => $newLikeValue,
				'date' => time(),
			]);
			if ( $likeObject ) {
				$like->getObject()->increment($newCounter);
			}

			$newState[$newLikeValue] = 1;
		}
		// delete like/dislike
		elseif ( $oldLikeValue === $newLikeValue ) {
			$deleted = DB::table($likeObject->getTable())->where([
				['objectType', $objectType,],
				['objectID', $objectID,],
				['steamID', auth()->id(),],
			])->delete();
			if ( $deleted ) {
				$like->getObject()->decrement($newCounter);
				$newState[$newLikeValue] = -1;
			}

			// delete notification
			DatabaseNotification::query()->where('id', $likeObject->notificationID)->delete();
		}
		// delete old like/dislike and add new
		else {
			$likeObject = $like->updateLike($newLikeValue);

			$like->getObject()->decrement($oldCounter);
			$like->getObject()->increment($newCounter);
			$newState[$oldLikeValue] = -1;
			$newState[$newLikeValue] = 1;

			// delete notification
			DatabaseNotification::query()->where('id', $likeObject->notificationID)->delete();
		}

		// send notification
		if ( $oldLikeValue !== $newLikeValue ) {
			/** @var SteamUser $user */
			$user = SteamUser::query()->find($like->getRecipientID());
			$user->notify(new LikeNotification($like, $likeObject));
		}

		return $newState;
	}
}