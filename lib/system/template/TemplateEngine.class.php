<?php

namespace system\template;

class TemplateEngine {
	public $variables = [];

	public function assign($vars, $value = null) {
		if ( is_string($vars) ) {
			$this->variables[$vars] = $value;
		}
		elseif ( is_array($vars) ) {
			foreach ( $vars as $var => $value ) {
				$this->variables[$var] = $value;
			}
		}
		else {
			throw new \Exception('system exception');
		}
	}

	public function display($templateName) {
		$context = new TemplateRenderer($templateName);
		$closure = function () {
			ob_start();
			include(MAIN_DIR.'templates/layout.php');

			return ob_end_flush();
		};
		$closure = $closure->bindTo($context, $context);
		$closure();
	}
}