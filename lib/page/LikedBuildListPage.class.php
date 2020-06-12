<?php

namespace page;

use data\build\LikedBuildList;
use system\Core;

class LikedBuildListPage extends MyBuildListPage {
	/** @inheritDoc */
	public $pageTitle = 'Liked Builds';

	/** @inheritDoc */
	protected function initObjectList() {
		$this->objectList = new LikedBuildList(Core::getUser()->steamID);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'LikedBuildList',
		]);
	}
}