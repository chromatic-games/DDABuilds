<?php

namespace page;

use data\bugReport\BugReport;
use data\bugReport\comment\BugReportCommentAction;
use data\bugReport\comment\BugReportCommentList;
use system\Core;
use system\exception\IllegalLinkException;
use system\exception\PermissionDeniedException;
use system\request\LinkHandler;
use system\util\HeaderUtil;

class BugReportPage extends SortablePage {
	/** @inheritDoc */
	public $loginRequired = true;

	/** @inheritDoc */
	public $objectListClassName = BugReportCommentList::class;

	/**
	 * @var BugReport
	 */
	public $bugReport;

	/** @inheritDoc */
	public $sqlOrderBy = 'time DESC';

	/** @inheritDoc */
	public $itemsPerPage = 10;

	/** @inheritDoc */
	protected function initObjectList() {
		parent::initObjectList();

		$this->objectList->getConditionBuilder()->add('bugReportID = ?', [$this->bugReport->getObjectID()]);
	}

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

		if ( $this->bugReport->steamID !== Core::getUser()->steamID && !Core::getUser()->isMaintainer() ) {
			throw new PermissionDeniedException();
		}

		$this->pageTitle = $this->bugReport->getTitle();

		// 60 seconds protection, prevent spam
		$statement = Core::getDB()->prepareStatement('SELECT time FROM bug_report br WHERE steamID = ? AND UNIX_TIMESTAMP() - br.time < 60 UNION SELECT time FROM bug_report_comment brc WHERE steamID = ? AND UNIX_TIMESTAMP() - brc.time < 60');
		$statement->execute([Core::getUser()->steamID, Core::getUser()->steamID]);
		if ( $statement->rowCount() ) {
			$seconds = 60 - (TIME_NOW - $statement->fetchColumn());
			Core::getTPL()->assign('error', 'You can post the next comment in '.$seconds.' seconds.');
			return;
		}

		if ( isset($_POST['description']) ) {
			$objectAction = new BugReportCommentAction([], 'create', [
				'data' => [
					'bugReportID' => $this->bugReport->getObjectID(),
					'steamID'     => Core::getUser()->steamID,
					'time'        => TIME_NOW,
					'description' => $_POST['description'],
				],
			]);
			$objectAction->executeAction();

			HeaderUtil::redirect(LinkHandler::getInstance()->getLink('BugReport', ['object' => $this->bugReport]));
		}
	}

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'bugReport' => $this->bugReport,
		]);
	}
}