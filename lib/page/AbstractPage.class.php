<?php

namespace page;

use system\Core;
use system\exception\PermissionDeniedException;

abstract class AbstractPage {
	/**
	 * indicates if you need to be logged in to access this page
	 *
	 * @var    boolean
	 */
	public $loginRequired = false;

	/**
	 * name of the template for the called page
	 *
	 * @var    string
	 */
	public $templateName = '';

	/**
	 * @var string
	 */
	public $pageTitle = '';

	public function __run() {
		$this->readParameters();
		$this->show();
	}

	public function readParameters() {
	}

	public function readData() {
	}

	/**
	 * assign variables to template engine, before display the template
	 *
	 * @throws \Exception
	 */
	public function assignVariables() {
		Core::getTPL()->assign([
			'pageTitle'    => $this->pageTitle,
			'templateName' => $this->templateName,
		]);
	}

	public function show() {
		if ( $this->loginRequired && !Core::getUser()->steamID ) {
			throw new PermissionDeniedException();
		}

		// read data
		$this->readData();

		// assign variables
		$this->assignVariables();

		if ( empty($this->templateName) ) {
			$classParts = explode('\\', get_class($this));
			$className = preg_replace('~(Form|Page)$~', '', array_pop($classParts));

			// check if this an *Edit page and use the add-template instead
			if ( substr($className, -4) == 'Edit' ) {
				$className = substr($className, 0, -4).'Add';
			}

			$this->templateName = lcfirst($className);
		}

		Core::getTPL()->display($this->templateName);
	}
}