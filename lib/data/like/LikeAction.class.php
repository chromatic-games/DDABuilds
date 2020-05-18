<?php

namespace data\like;

use data\DatabaseObjectAction;
use data\like\object\AbstractLike;
use system\Core;
use system\exception\ParentClassException;
use system\exception\PermissionDeniedException;
use system\exception\UserInputException;

class LikeAction extends DatabaseObjectAction {
	/**
	 * @var AbstractLike
	 */
	public $likeObject;

	public function validateLike() {
		if ( empty($this->parameters['objectID']) ) {
			throw new UserInputException('objectID');
		}

		if ( empty($this->parameters['objectType']) ) {
			throw new UserInputException('objectType');
		}

		if ( empty($this->parameters['likeType']) ) {
			throw new UserInputException('likeType');
		}

		if ( $this->parameters['likeType'] !== 'like' && $this->parameters['likeType'] !== 'dislike' ) {
			throw new UserInputException('likeType');
		}

		$className = 'data\\like\\object\\'.ucfirst($this->parameters['objectType']).'Like';
		if ( !class_exists($className) ) {
			throw new UserInputException('objectType', 'invalid');
		}
		elseif ( !is_subclass_of($className, AbstractLike::class) ) {
			throw new ParentClassException($className, AbstractLike::class);
		}

		$this->likeObject = new $className($this->parameters['objectID']);
		if ( !$this->likeObject->canLike(Core::getUser()->steamID) ) {
			throw new PermissionDeniedException();
		}

		if ( $this->parameters['likeType'] === 'dislike' && !$this->likeObject->isEnabledDislikes() ) {
			throw new PermissionDeniedException();
		}
	}

	public function like() {
		$oldLikeValue = $this->likeObject->getLikeValue(Core::getUser()->steamID);
		$newLikeValue = $this->parameters['likeType'] === 'like' ? AbstractLike::LIKE : AbstractLike::DISLIKE;
		$oldCounter = $oldLikeValue === AbstractLike::LIKE ? 'likes' : 'dislikes';
		$newCounter = $newLikeValue === AbstractLike::LIKE ? 'likes' : 'dislikes';
		$newState = [];
		if ( $oldLikeValue === null ) {
			$statement = Core::getDB()->prepareStatement('INSERT INTO `like` (objectType, objectID, steamID, likeValue, date) VALUES (?,?,?,?, CURRENT_DATE());');
			$statement->execute([
				$this->likeObject->getObjectType(),
				$this->likeObject->getObject()->getObjectID(),
				Core::getUser()->steamID,
				$newLikeValue,
			]);
			$this->likeObject->getObject()->updateCounters([
				$newCounter => 1,
			]);
			$newState[$newLikeValue] = 1;
		}
		// delete like/dislike
		elseif ( $oldLikeValue === $newLikeValue ) {
			$statement = Core::getDB()->prepareStatement('DELETE FROM `like` WHERE objectType = ? AND objectID = ? AND steamID = ?;');
			$statement->execute([
				$this->likeObject->getObjectType(),
				$this->likeObject->getObject()->getObjectID(),
				Core::getUser()->steamID,
			]);
			$this->likeObject->getObject()->updateCounters([
				$newCounter => -1,
			]);
			$newState[$newLikeValue] = -1;
		}
		// delete old like/dislike and add new
		else {
			$statement = Core::getDB()->prepareStatement('UPDATE `like` SET likeValue = ?, date = CURRENT_DATE() WHERE objectType = ? AND objectID = ? AND steamID = ?;');
			$statement->execute([
				$newLikeValue,
				$this->likeObject->getObjectType(),
				$this->likeObject->getObject()->getObjectID(),
				Core::getUser()->steamID,
			]);
			$this->likeObject->getObject()->updateCounters([
				$oldCounter => -1,
				$newCounter => 1,
			]);
			$newState[$oldLikeValue] = -1;
			$newState[$newLikeValue] = 1;
		}

		return $newState;
	}
}