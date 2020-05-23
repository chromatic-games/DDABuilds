<?php

namespace page;

use system\Core;

class MyBugReportListPage extends BugReportListPage {
	/** @inheritDoc */
	public $loginRequired = true;

	/** @inheritDoc */
	public $pageTitle = 'My Bug Reports';

	/** @inheritDoc */
	public $templateName = 'bugReportList';

	/** @inheritDoc */
	public function readParameters() {
		SortablePage::readParameters();
	}

	/** @inheritDoc */
	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('steamID = ?', [Core::getUser()->steamID]);
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'MyBugReportList',
		]);
	}
}