<?php

namespace page;

use data\build\FavoriteBuildList;
use data\build\LikedBuildList;
use system\Core;

class MyLikedBuildListPage extends MyBuildListPage {
	/** @inheritDoc */
	public $pageTitle = 'My Liked Builds';

	/** @inheritDoc */
	protected function initObjectList() {
		$this->objectList = new LikedBuildList(Core::getUser()->steamID);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'MyLikedBuildList',
		]);
	}
}