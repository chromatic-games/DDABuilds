<?php

namespace data\build;

use data\DatabaseObject;
use data\difficulty\Difficulty;
use data\IRouteObject;
use data\map\Map;

/**
 * TODO add here @ property-read for all database columns
 *
 * Class Build
 * @package data\build
 *
 * @property-read string $date
 * @property-read string $author
 * @property-read string $name
 * @property-read integer $views
 * @property-read integer $map
 * @property-read integer $difficulty
 * @property-read integer $votes
 */
class Build extends DatabaseObject implements IRouteObject {
	protected static $databaseTableName = 'builds';

	protected static $databaseTableIndexName = 'id';

	public function getDate() {
		return date('d F Y', strtotime($this->date));
	}

	public function getThumbnail() {
		$filename = 'assets/images/thumbnails/'.$this->getObjectID().'.png';
		if ( !file_exists(MAIN_DIR.$filename) ) {
			return 'https://via.placeholder.com/262x262?text=Placeholder';
		}

		return $filename;
	}

	/**
	 * get the name of map
	 * TODO use runtimecache/cachebuilder stuff
	 *
	 * @return Map
	 */
	public function getMap() {
		return new Map($this->map);
	}

	/**
	 * get the name of difficulty
	 * TODO use runtimecache/cachebuilder stuff
	 *
	 * @return Difficulty
	 */
	public function getDifficulty() {
		return new Difficulty($this->difficulty);
	}

	public function getTitle() {
		return $this->name;
	}
}