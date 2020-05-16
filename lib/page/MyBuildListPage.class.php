<?php

namespace page;

use system\Core;

class MyBuildListPage extends BuildListPage {
	public $loginRequired = true;

	public $templateName = 'buildList';

	public $showFilter = false;

	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('fk_user = ?', [Core::getUser()->steamID]);
	}

	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'hideAuthor' => true,
			'controller' => 'MyBuildList',
		]);
	}
}