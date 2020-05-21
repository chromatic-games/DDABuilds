<?php

namespace page;

use system\Core;

/**
 * page for my builds
 */
class MyBuildListPage extends BuildListPage {
	/** @inheritDoc */
	public $loginRequired = true;

	/** @inheritDoc */
	public $templateName = 'buildList';

	/** @inheritDoc */
	public $showFilter = false;

	/** @inheritDoc */
	public $pageTitle = 'My Builds';

	/** @inheritDoc */
	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('fk_user = ?', [Core::getUser()->steamID]);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'hideAuthor' => true,
			'controller' => 'MyBuildList',
		]);
	}
}