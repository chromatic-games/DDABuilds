<?php

namespace data\build;

/**
 * present a list of favorite builds from a specific steam user
 *
 * @package data\build
 */
class FavoriteBuildList extends BuildList {
	/**
	 * @param string $steamID
	 */
	public function __construct($steamID) {
		parent::__construct();

		$leftJoin = "LEFT JOIN build_watch ON build_watch.buildID = builds.id AND build_watch.steamID = ".$steamID;
		$this->sqlJoins = $leftJoin;
		$this->sqlConditionJoins = $leftJoin;

		$this->getConditionBuilder()->add('build_watch.steamID IS NOT NULL');
	}
}