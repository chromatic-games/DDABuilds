<?php

namespace page;

use data\bugReport\BugReportList;
use system\Core;
use system\exception\IllegalLinkException;

class BugReportListPage extends SortablePage {
	/** @inheritDoc */
	public $pageTitle = 'Bug Reports';

	/** @inheritDoc */
	public $objectListClassName = BugReportList::class;

	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( !Core::getUser()->isMaintainer() ) {
			throw new IllegalLinkException();
		}
	}

	/** @inheritDoc */
	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->sqlOrderBy = 'status ASC, time DESC';
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'controller' => 'BugReportList',
		]);
	}
}