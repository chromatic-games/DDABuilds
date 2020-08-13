<?php

namespace data\build;

/**
 * present a list of liked builds from a specific steam user
 *
 * @package data\build
 */
class LikedBuildList extends BuildList {
	/**
	 * @param string $steamID
	 */
	public function __construct($steamID) {
		parent::__construct();

		$leftJoin = "LEFT JOIN `like` as object_like ON object_like.likeValue = 1 AND object_like.objectType = 'build' AND object_like.objectID = builds.id AND object_like.steamID = ".$steamID;
		$this->sqlJoins = $leftJoin;
		$this->sqlConditionJoins = $leftJoin;

		$this->getConditionBuilder()->add('object_like.steamID IS NOT NULL');
	}
}