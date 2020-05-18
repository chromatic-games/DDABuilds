<?php

namespace data\like\object;

use data\DatabaseObject;
use system\Core;
use system\exception\IllegalLinkException;
use system\exception\NamedUserException;

abstract class AbstractLike {
	const LIKE    = 1;

	const DISLIKE = -1;

	protected static $baseClass;

	protected static $enabledDislikes = false;

	/**
	 * @var string
	 */
	public $objectType;

	protected $object;

	protected $objectID;

	public function __construct($objectID) {
		if ( empty(static::$baseClass) ) {
			throw new NamedUserException('Base class not specified');
		}

		$this->objectID = $objectID;
		if ( !$this->getObject()->getObjectID() ) {
			throw new IllegalLinkException();
		}

		$classParts = explode('\\', get_class($this));
		$this->objectType = lcfirst(substr(array_pop($classParts), 0, -4));
	}

	/**
	 * can $steamID like this object?
	 *
	 * @param string $steamID
	 *
	 * @return boolean
	 */
	abstract public function canLike($steamID);

	/**
	 * @return DatabaseObject
	 */
	public function getObject() {
		if ( $this->object === null ) {
			$this->object = new static::$baseClass($this->objectID);
		}

		return $this->object;
	}

	/**
	 * @param string $steamID
	 *
	 * @return null|integer
	 * @throws \Exception
	 */
	public function getLikeValue($steamID) {
		$statement = Core::getDB()->prepareStatement('SELECT * FROM `like` WHERE objectType = ? AND objectID = ? AND steamID = ?');
		$statement->execute([
			$this->objectType,
			$this->objectID,
			$steamID,
		]);
		$data = $statement->fetch();

		return $data !== null ? $data['likeValue'] : null;
	}

	/**
	 * @return bool
	 */
	public function isEnabledDislikes() {
		return self::$enabledDislikes;
	}

	/**
	 * @return string
	 */
	public function getObjectType() {
		return $this->objectType;
	}
}