<?php

namespace system\template;

class TemplateEngine {
	public $variables = [];

	public function assignVariables($vars, $value = null) {
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
}