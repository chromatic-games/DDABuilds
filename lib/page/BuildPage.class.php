<?php

namespace page;

use data\build\Build;
use system\Core;
use system\exception\IllegalLinkException;

class BuildPage extends BuildAddPage {
	public $loginRequired = false;

	public $templateName = 'buildAdd';

	/** @var Build */
	public $build;

	/** @var string view mode (edit/view) */
	public $action = 'view';

	public function readParameters() {
		AbstractPage::readParameters();

		if ( !isset($_REQUEST['id']) ) {
			throw new IllegalLinkException();
		}

		$this->build = new Build($_REQUEST['id']);
		if ( !$this->build->getObjectID() ) {
			throw new IllegalLinkException();
		}

		$this->map = $this->build->getMap();
		if ( $this->build->isCreator() && !isset($_REQUEST['view']) ) {
			$this->action = 'edit';
		}
	}

	public function readData() {
		parent::readData();

		$this->buildName = $this->build->name;
		$this->author = $this->build->author;
		$this->expPerRun = $this->build->expPerRun;
		$this->timePerRun = $this->build->timePerRun;
		$this->description = $this->build->description;
	}

	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'build'  => $this->build,
			'action' => $this->action,
		]);
	}
}