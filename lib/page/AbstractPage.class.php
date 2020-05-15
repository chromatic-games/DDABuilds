<?php

namespace page;

use system\Core;
use system\template\TemplateRenderer;

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

	public function assignVariables() {
		Core::$tplVariables = [
			'pageTitle'    => $this->pageTitle,
			'templateName' => $this->templateName,
		];
	}

	public function show() {
		if ( $this->loginRequired && !Core::getUser()->steamID ) {
			throw new \Exception('PermissionDeniedException'); // todo
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

		$context = new TemplateRenderer($this->templateName);
		$closure = function () {
			ob_start();
			include(MAIN_DIR.'templates/layout.php');

			return ob_end_flush();
		};
		$closure = $closure->bindTo($context, $context);
		$closure();
	}
}