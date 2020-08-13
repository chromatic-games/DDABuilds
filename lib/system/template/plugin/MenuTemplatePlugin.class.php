<?php

namespace system\template\plugin;

use system\request\RouteHandler;

/**
 * render a menu item with active class if menu item is active
 */
class MenuTemplatePlugin {
	/**
	 * @param array  $controller
	 * @param string $link
	 * @param string $text
	 *
	 * @return string
	 * @throws \Exception
	 */
	public function execute(array $controller, $link, $text) {
		return '<li'.(in_array(RouteHandler::getInstance()->getActiveController(), $controller) ? ' class="active"' : '').'><a href="'.$link.'">'.$text.'</a></li>';
	}
}