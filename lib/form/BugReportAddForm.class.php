<?php

namespace form;

use data\bugReport\BugReport;
use data\bugReport\BugReportAction;
use system\Core;
use system\exception\NamedUserException;
use system\exception\UserInputException;
use system\util\HeaderUtil;

class BugReportAddForm extends AbstractForm {
	/** @inheritDoc */
	public $loginRequired = true;

	/** @inheritDoc */
	public $pageTitle = 'Report Issue';

	/** @var string */
	public $title = '';

	/** @var string */
	public $description = '';

	public function readParameters() {
		parent::readParameters();

		// 60 seconds protection, prevent spam
		$statement = Core::getDB()->prepareStatement('SELECT time FROM bug_report WHERE steamID = ? AND UNIX_TIMESTAMP() - time < 60');
		$statement->execute([Core::getUser()->steamID]);
		if ( $statement->rowCount() ) {
			$seconds = 60 - (TIME_NOW - $statement->fetchColumn());
			throw new NamedUserException('Please wait '.$seconds.' seconds for next issue report.');
		}
	}

	/** @inheritDoc */
	public function readFormParameters() {
		parent::readFormParameters();

		if ( isset($_POST['title']) ) {
			$this->title = trim($_POST['title']);
		}
		if ( isset($_POST['description']) ) {
			$this->description = trim($_POST['description']);
		}
	}

	/** @inheritDoc */
	public function validate() {
		parent::validate();

		if ( !isset($_POST['checkbox']) ) {
			$this->errors['checkbox'] = new UserInputException('checkbox');
		}

		if ( empty($this->title) ) {
			$this->errors['title'] = new UserInputException('title');
		}
	}

	/** @inheritDoc */
	public function save() {
		parent::save();

		$objectAction = new BugReportAction([], 'create', [
			'data' => [
				'steamID'     => Core::getUser()->steamID,
				'status'      => BugReport::STATUS_OPEN,
				'time'        => TIME_NOW,
				'title'       => $this->title,
				'description' => $this->description,
			],
		]);
		$bugReport = $objectAction->executeAction()['returnValues'];

		$this->saved();

		HeaderUtil::redirect($bugReport->getLink());
		exit;
	}

	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'title' => $this->title,
			'description' => $this->description,
		]);
	}
}