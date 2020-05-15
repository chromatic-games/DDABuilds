<?php

namespace system\template;

use system\Core;

class TemplateRenderer {
	protected $templateName;

	public function __construct($templateName) {
		$this->templateName = $templateName;
	}

	public function __get($name) {
		if ( $name === 'content' ) {
			return $this->__toString();
		}
		elseif ( isset(Core::$tplVariables[$name]) ) {
			return Core::$tplVariables[$name];
		}

		return null;
	}

	public function render($templateName) {
		return (new TemplateRenderer($templateName))->__toString();
	}

	public function __toString() {
		ob_start();
		require(MAIN_DIR.'templates/'.$this->templateName.'.php');
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}