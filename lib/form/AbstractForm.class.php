<?php

namespace form;

use page\AbstractPage;
use system\Core;

abstract class AbstractForm extends AbstractPage {
	/** @var array */
	public $errors = [];

	public function submit() {
		$this->readFormParameters();

		$this->validate();
		if ( empty($this->errors) ) {
			$this->save();
		}
	}

	public function readFormParameters() {
	}

	public function validate() {
	}

	public function save() {
	}

	public function saved() {
	}

	public function readData() {
		if ( !empty($_POST) || !empty($_FILES) ) {
			$this->submit();
		}

		parent::readData();
	}

	public function assignVariables() {
		parent::assignVariables();

		Core::getTPL()->assign([
			'formErrors' => $this->errors,
		]);
	}
}