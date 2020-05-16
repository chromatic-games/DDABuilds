<?php

namespace system\template;

use system\Core;
use system\exception\NamedUserException;
use system\request\LinkHandler;
use system\util\StringUtil;

class TemplateRenderer {
	protected $templateName;

	/**
	 * @var array
	 */
	protected $__variables = [];

	public function __construct($templateName, array $variables = []) {
		$this->templateName = $templateName;
		$this->__variables = $variables;

		if ( !file_exists($this->getFileName()) ) {
			throw new NamedUserException('Cant find template '.$this->templateName);
		}
	}

	public function escapeHtml($string) {
		return StringUtil::encodeHTML($string);
	}

	/**
	 * @param string $name
	 *
	 * @return mixed
	 * @throws NamedUserException
	 */
	public function __get($name) {
		if ( $name === 'content' ) {
			return $this->__toString();
		}
		elseif ( isset($this->__variables[$name]) ) {
			return $this->__variables[$name];
		}
		elseif ( isset(Core::getTPL()->variables[$name]) ) {
			return Core::getTPL()->variables[$name];
		}

		return null;
	}

	public function render($templateName, array $variables = []) {
		return (new TemplateRenderer($templateName, $variables))->__toString();
	}

	public function number($number) {
		return StringUtil::formatNumeric($number);
	}

	public function renderPages(array $options) {
		$options = array_merge([
			'controller'           => null,
			'controllerParameters' => [],
			'url'                  => '',
			'print'                => false,
			'assign'               => null,
		], $options);

		if ( $this->pages === null ) {
			throw new NamedUserException('invalid pages');
		}

		if ( $this->pages === 1 ) {
			return '';
		}

		$html = '<ul class="pagination">';
		for ( $page = 1;$page <= $this->pages;$page++ ) {
			$isActive = $this->pageNo === $page;
			$html .= '<li'.($isActive ? ' class="active"' : '').'>';
			if ( !$isActive ) {
				$html .= '<a href="'.LinkHandler::getInstance()->getLink($options['controller'], $options['controllerParameters'], sprintf($options['url'], $page)).'">'.$page.'</a>';
			}
			else {
				$html .= '<a>'.$page.'</a>';
			}
			$html .= '</li>';
		}
		$html .= '</ul>';

		if ( $options['assign'] && is_string($options['assign']) ) {
			Core::getTPL()->assign($options['assign'], $html);
		}
		if ( $options['print'] ) {
			echo $html;
		}

		return $html;
	}

	public function getFileName() {
		return MAIN_DIR.'templates/'.$this->templateName.'.php';
	}

	public function __toString() {
		ob_start();
		require($this->getFileName());
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}