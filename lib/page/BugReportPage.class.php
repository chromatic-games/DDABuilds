<?php

namespace page;

use data\bugReport\BugReport;
use system\Core;
use system\exception\IllegalLinkException;
use system\exception\PermissionDeniedException;

class BugReportPage extends AbstractPage {
	/** @inheritDoc */
	public $loginRequired = true;

	/**
	 * @var BugReport
	 */
	public $bugReport;

	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( !isset($_REQUEST['id']) ) {
			throw new IllegalLinkException();
		}

		$this->bugReport = new BugReport($_REQUEST['id']);
		if ( !$this->bugReport->getObjectID() ) {
			throw new IllegalLinkException();
		}

		if ( $this->bugReport->steamID !== Core::getUser()->steamID && !Core::getUser()->isMaintainer()) {
			throw new PermissionDeniedException();
		}

		$this->pageTitle = $this->bugReport->getTitle();
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'bugReport' => $this->bugReport,
		]);
	}
}