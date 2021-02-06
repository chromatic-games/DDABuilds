<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Like\AbstractLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class LikeController extends AbstractController {
	public function like(Request $request) {
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

		/** @var AbstractLike $likeObject */
		$likeObject = new $className($objectID);
		$this->authorize('like', $likeObject->getObject());

		if ( $likeType === 'dislike' && !$likeObject->isEnabledDislikes() ) {
			throw new BadRequestHttpException('Dislike is disabled.');
		}

		$objectLikeObject = $likeObject->getLikeObject(auth()->id());
		$oldLikeValue = $likeObject->getLikeValue(auth()->id());
		$newLikeValue = $likeType === 'like' ? AbstractLike::LIKE : AbstractLike::DISLIKE;
		$oldCounter = $oldLikeValue === AbstractLike::LIKE ? 'likes' : 'dislikes';
		$newCounter = $newLikeValue === AbstractLike::LIKE ? 'likes' : 'dislikes';
		$newState = [];

		// if ( $likeObject->getObjectType() === 'comment' ) {
		// 	$recipientSteamID = $likeObject->getObject()->steamID;
		// }
		// else {
		// 	$recipientSteamID = $likeObject->getObject()->fk_user;
		// }

		if ( $oldLikeValue === null ) {
			if ( Like::create([
				'objectType' => $likeObject->getObjectType(),
				'objectID' => $likeObject->getObjectID(),
				'steamID' => auth()->id(),
				'likeValue' => $newLikeValue,
				'date' => time(),
			]) ) {
				$likeObject->getObject()->increment($newCounter);
			}

			$newState[$newLikeValue] = 1;
			// $likeObject->createNotification($recipientSteamID, Core::getUser()->steamID, $newLikeValue);
		}
		// delete like/dislike
		elseif ( $oldLikeValue === $newLikeValue ) {
			$deleted = DB::table($objectLikeObject->getTable())->where([
				['objectType', $objectType,],
				['objectID', $objectID,],
				['steamID', auth()->id(),],
			])->delete();
			if ( $deleted ) {
				$likeObject->getObject()->decrement($newCounter);
				$newState[$newLikeValue] = -1;
			}
			// $likeObject->deleteNotification($recipientSteamID, Core::getUser()->steamID);
		}
		// delete old like/dislike and add new
		else {
			DB::table($objectLikeObject->getTable())->where([
				['objectType', $objectType,],
				['objectID', $objectID,],
				['steamID', auth()->id(),],
			])->update([
				'likeValue' => $newLikeValue,
				'date' => time(),
			]);
			$likeObject->getObject()->decrement($oldCounter);
			$likeObject->getObject()->increment($newCounter);
			$newState[$oldLikeValue] = -1;
			$newState[$newLikeValue] = 1;

			// $updated = $likeObject->updateNotification($recipientSteamID, Core::getUser()->steamID, $newLikeValue);
			// if ( $updated === 0 ) {
			// 	$likeObject->createNotification($recipientSteamID, COre::getUser()->steamID, $newLikeValue);
			// }
		}

		return $newState;
	}
}