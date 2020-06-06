<?php

namespace system\template;

use Exception;
use system\exception\NamedUserException;

/**
 * simple TemplateEngine
 *
 * @package system\template
 */
class TemplateEngine {
	/** @var array */
	public $variables = [];

	/** @var string */
	protected static $output = '';

	/**
	 * assign variable(s)
	 *
	 * @param array|string $vars
	 * @param mixed        $value
	 *
	 * @throws Exception
	 */
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
			throw new Exception('system exception');
		}
	}

	/**
	 * render the given template
	 *
	 * @param string $templateName
	 * @param array  $variables
	 *
	 * @return false|string
	 * @throws NamedUserException
	 */
	public function render($templateName, array $variables = []) {
		return (new TemplateRenderer($templateName, $variables))->__toString();
	}

	/**
	 * relocate the script tags to end of body
	 *
	 * @param string $output
	 *
	 * @return string|string[]
	 */
	protected static function parseOutput($output) {
		self::$output = $output;

		// force javascript relocation
		self::$output = preg_replace('~<script([^>]*)>~', '<script data-relocate="true"\\1>', self::$output);

		// move script tags to the bottom of the page
		$javascript = [];
		self::$output = preg_replace_callback('~(?P<conditionBefore><!--\[IF [^<]+\s*)?<script data-relocate="true"(?P<script>.*?</script>\s*)(?P<conditionAfter><!\[ENDIF]-->\s*)?~s', function ($matches) use (&$javascript) {
			$match = '';
			if ( isset($matches['conditionBefore']) ) {
				$match .= $matches['conditionBefore'];
			}
			$match .= '<script'.$matches['script'];
			if ( isset($matches['conditionAfter']) ) {
				$match .= $matches['conditionAfter'];
			}

			$javascript[] = $match;

			return '';
		}, self::$output);

		self::$output = str_replace('<!-- JAVASCRIPT_RELOCATE_POSITION -->', implode("\n", $javascript), self::$output);

		return self::$output;
	}

	public function display($templateName) {
		ob_start([TemplateEngine::class, 'parseOutput']);

		$context = new TemplateRenderer($templateName);
		$closure = function () {
			include(MAIN_DIR.'templates/layout.php');

			return ob_end_flush();
		};
		$closure = $closure->bindTo($context, $context);
		$closure();
	}
}