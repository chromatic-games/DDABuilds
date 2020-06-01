<?php

namespace page;

use data\build\FavoriteBuildList;
use system\Core;

class MyFavoriteBuildListPage extends MyBuildListPage {
	/** @inheritDoc */
	public $objectListClassName = FavoriteBuildList::class;

	/** @inheritDoc */
	public $pageTitle = 'My Favorite Builds';

	/** @inheritDoc */
	protected function initObjectList() {
		$this->objectList = new FavoriteBuildList(Core::getUser()->steamID);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'MyFavoriteBuildList',
		]);
	}
}