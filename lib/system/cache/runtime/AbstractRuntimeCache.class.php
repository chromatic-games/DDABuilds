<?php

namespace system\cache\runtime;

use data\DatabaseObject;
use data\DatabaseObjectList;
use system\SingletonFactory;

/**
 * Class AbstractRuntimeCache
 * @package system\cache\runtime
 */
abstract class AbstractRuntimeCache extends SingletonFactory {
	/**
	 * name of the DatabaseObjectList class
	 * @var string
	 */
	protected $listClassName = '';

	/**
	 * ids of objects which will be fetched next
	 * @var integer[]
	 */
	protected $objectIDs = [];

	/**
	 * cached DatabaseObject objects
	 * @var DatabaseObject[]
	 */
	protected $objects = [];

	/**
	 * Caches the given object id so that during the next object fetch, the object with
	 * this id will also be fetched.
	 *
	 * @param integer $objectID
	 */
	public function cacheObjectID($objectID) {
		$this->cacheObjectIDs([$objectID]);
	}

	/**
	 * Caches the given object ids so that during the next object fetch, the objects with
	 * these ids will also be fetched.
	 *
	 * @param integer[] $objectIDs
	 */
	public function cacheObjectIDs(array $objectIDs) {
		foreach ( $objectIDs as $objectID ) {
			if ( !array_key_exists($objectID, $this->objects) && !isset($this->objectIDs[$objectID]) ) {
				$this->objectIDs[$objectID] = $objectID;
			}
		}
	}

	/**
	 * Fetches the objects for the pending object ids.
	 */
	protected function fetchObjects() {
		$objectList = $this->getObjectList();
		$objectList->setObjectIDs(array_values($this->objectIDs));
		$objectList->readObjects();
		$this->objects += $objectList->getObjects();

		// create null entries for non-existing objects
		foreach ( $this->objectIDs as $objectID ) {
			if ( !array_key_exists($objectID, $this->objects) ) {
				$this->objects[$objectID] = null;
			}
		}

		$this->objectIDs = [];
	}

	/**
	 * Returns all currently cached objects.
	 *
	 * @return DatabaseObject[]
	 */
	public function getCachedObjects() {
		return $this->objects;
	}

	/**
	 * Returns the object with the given id or null if no such object exists.
	 * If the given object id should not have been cached before, it will be cached
	 * during this method call and the object, if existing, will be returned.
	 *
	 * @param integer $objectID
	 *
	 * @return DatabaseObject|null
	 */
	public function getObject($objectID) {
		if ( array_key_exists($objectID, $this->objects) ) {
			return $this->objects[$objectID];
		}

		$this->cacheObjectID($objectID);

		$this->fetchObjects();

		return $this->objects[$objectID];
	}

	/**
	 * Returns a database object list object to fetch cached objects.
	 *
	 * @return DatabaseObjectList
	 */
	protected function getObjectList() {
		return new $this->listClassName;
	}

	/**
	 * Returns the objects with the given ids. If an object does not exist, the array element
	 * wil be null.
	 * If the given object ids should not have been cached before, they will be cached
	 * during this method call and the objects, if existing, will be returned.
	 *
	 * @param integer[] $objectIDs
	 *
	 * @return DatabaseObject[]
	 */
	public function getObjects(array $objectIDs) {
		$objects = [];

		// set already cached objects
		foreach ( $objectIDs as $key => $objectID ) {
			if ( array_key_exists($objectID, $this->objects) ) {
				$objects[$objectID] = $this->objects[$objectID];
				unset($objectIDs[$key]);
			}
		}

		if ( !empty($objectIDs) ) {
			$this->cacheObjectIDs($objectIDs);

			$this->fetchObjects();

			// set newly loaded cached objects
			foreach ( $objectIDs as $objectID ) {
				$objects[$objectID] = $this->objects[$objectID];
			}
		}

		return $objects;
	}

	/**
	 * Removes the object with the given id from the runtime cache if it has already been loaded.
	 *
	 * @param integer $objectID
	 */
	public function removeObject($objectID) {
		$this->removeObjects([$objectID]);
	}

	/**
	 * Removes the objects with the given ids from the runtime cache if they have already been loaded.
	 *
	 * @param integer[] $objectIDs
	 */
	public function removeObjects(array $objectIDs) {
		foreach ( $objectIDs as $objectID ) {
			unset($this->objects[$objectID]);
		}
	}
}