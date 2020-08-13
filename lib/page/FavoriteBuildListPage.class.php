<?php

namespace page;

use data\build\FavoriteBuildList;
use system\Core;

class FavoriteBuildListPage extends MyBuildListPage {
	/** @inheritDoc */
	public $pageTitle = 'Favorite Builds';

	/** @inheritDoc */
	protected function initObjectList() {
		$this->objectList = new FavoriteBuildList(Core::getUser()->steamID);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'FavoriteBuildList',
		]);
	}
}