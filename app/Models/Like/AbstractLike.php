<?php

namespace App\Models\Like;

use App\Models\Like;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

abstract class AbstractLike {
	public const LIKE = 1;

	public const DISLIKE = -1;

	protected static $baseClass;

	protected static $enabledDislikes = false;

	public $objectType;

	protected $object;

	protected $objectID;

	/** @var Like */
	protected $likeObject;

	public function __construct(int $objectID) {
		if ( empty(static::$baseClass) ) {
			throw new BadRequestException('Base class not specified');
		}

		$this->objectID = $objectID;
		if ( !is_subclass_of(static::$baseClass, ILikeableModel::class) ) {
			throw new BadRequestException(sprintf('Class \'%s\' does not extend class \'%s\'.', static::$baseClass, ILikeableModel::class));
		}
		elseif ( !$this->getObject() ) {
			throw new BadRequestException('objectID not found');
		}

		$classParts = explode('\\', get_class($this));
		$this->objectType = lcfirst(substr(array_pop($classParts), 0, -4));
	}

	abstract public function getNotificationData() : array;

	public function getRecipientID() {
		return $this->getObject() ? $this->getObject()->steamID : null;
	}

	public function getLikeValue() : ?int {
		$likeObject = $this->getLikeObject();

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

	/**
	 * @return null|ILikeableModel|Builder
	 */
	public function getObject() : ?ILikeableModel {
		if ( $this->object === null ) {
			$this->object = static::$baseClass::find($this->objectID);
		}

		return $this->object;
	}

	public function getLikeObject() : ?Like {
		if ( $this->likeObject === null ) {
			$this->likeObject = Like::query()->where([
				['objectType', $this->objectType],
				['objectID', $this->objectID],
				['steamID', auth()->id()],
			])->first();
		}

		return $this->likeObject;
	}

	public function updateLike(int $newLikeValue) {
		$likeObject = $this->getLikeObject();
		DB::table($likeObject->getTable())->where([
			['objectType', $this->objectType,],
			['objectID', $this->objectID,],
			['steamID', auth()->id(),],
		])->update([
			'likeValue' => $newLikeValue,
			'date' => time(),
		]);

		// reset like object
		$this->likeObject = null;

		return $this->getLikeObject();
	}
}