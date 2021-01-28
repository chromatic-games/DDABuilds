<?php

namespace App\Models\Like;

use App\Models\AbstractModel;
use App\Models\Like;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class AbstractLike {
	public const LIKE = 1;

	public const DISLIKE = -1;

	protected static $baseClass;

	protected static $enabledDislikes = false;

	public $objectType;

	protected $steamID;

	protected $object;

	protected $objectID;

	/** @var Like */
	protected $likeObject;

	// abstract public function createNotification($recipient, $steamID, $likeValue);

	// abstract public function deleteNotification($recipient, $steamID);

	// abstract public function updateNotification($recipient, $steamID, $likeValue);

	public function __construct($objectID) {
		if ( empty(static::$baseClass) ) {
			throw new BadRequestException('Base class not specified');
		}

		$this->objectID = $objectID;
		if ( !$this->getObject() ) {
			throw new BadRequestException('objectID not found');
		}
		elseif ( !is_subclass_of(self::$baseClass, ILikeableModel::class)) {

		}

		$classParts = explode('\\', get_class($this));
		$this->objectType = lcfirst(substr(array_pop($classParts), 0, -4));
	}

	public function getObject() : ?AbstractModel {
		if ( $this->object === null ) {
			$this->object = static::$baseClass::find($this->objectID);
		}

		return $this->object;
	}

	public function getLikeObject(string $steamID) : ?Like {
		if ( $this->steamID !== $steamID ) {
			$this->steamID = $steamID;
			$this->likeObject = null;
		}

		if ( $this->likeObject === null ) {
			$this->likeObject = Like::where([
				['objectType', $this->objectType],
				['objectID', $this->objectID],
				['steamID', $steamID],
			])->first();
		}

		return $this->likeObject;
	}

	public function getLikeValue($steamID) : ?int {
		$likeObject = $this->getLikeObject($steamID);

		return $likeObject ? $likeObject->likeValue : null;
	}

	public function getObjectID() {
		return $this->objectID;
	}

	public function isEnabledDislikes() {
		return static::$enabledDislikes;
	}

	public function getObjectType() {
		return $this->objectType;
	}
}