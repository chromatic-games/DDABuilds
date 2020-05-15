<?php

namespace form;

use page\AbstractPage;

abstract class AbstractForm extends AbstractPage {
	public function submit() {
		$this->readFormParameters();

		try {
			$this->validate();// no errors from validation -> save
			$this->save();
		} catch ( \Exception $e ) {
			// todo set error field
		}
	}

	public function readFormParameters() {
	}

	public function validate() {
	}

	public function save() {
	}

	public function readData() {
		if ( !empty($_POST) || !empty($_FILES) ) {
			$this->submit();
		}

		parent::readData();
	}
}