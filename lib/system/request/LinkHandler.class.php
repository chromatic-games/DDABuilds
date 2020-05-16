<?php

namespace system\request;

use data\IRouteObject;
use system\SingletonFactory;

class LinkHandler extends SingletonFactory {
	protected $titleRegex = '/[^\p{L}\p{N}]+/u';

	/**
	 * Returns a relative link.
	 *
	 * @param string $controller
	 * @param array  $parameters
	 * @param string $url
	 *
	 * @return    string
	 */
	public function getLink($controller = null, array $parameters = [], $url = '') {
		$anchor = '';
		$isRaw = false;
		$encodeTitle = true;

		// enforce a certain level of sanitation and protection for links embedded in emails
		if ( isset($parameters['isEmail']) && (bool) $parameters['isEmail'] ) {
			$parameters['forceFrontend'] = true;
			unset($parameters['isEmail']);
		}

		if ( isset($parameters['isRaw']) ) {
			$isRaw = $parameters['isRaw'];
			unset($parameters['isRaw']);
		}
		if ( isset($parameters['encodeTitle']) ) {
			$encodeTitle = $parameters['encodeTitle'];
			unset($parameters['encodeTitle']);
		}

		// remove anchor before parsing
		if ( ($pos = strpos($url, '#')) !== false ) {
			$anchor = substr($url, $pos);
			$url = substr($url, 0, $pos);
		}

		// default controller
		if ( $controller === null ) {
			$controller = 'Index';
		}

		// handle object
		if ( isset($parameters['object']) ) {
			if ( $parameters['object'] instanceof IRouteObject ) {
				$parameters['id'] = $parameters['object']->getObjectID();
				$parameters['title'] = $parameters['object']->getTitle();
			}

			unset($parameters['object']);
		}

		if ( isset($parameters['title']) ) {
			// remove illegal characters
			$parameters['title'] = trim(preg_replace($this->titleRegex, '-', $parameters['title']), '-');

			// trim to 80 characters
			$parameters['title'] = rtrim(mb_substr($parameters['title'], 0, 80), '-');
			$parameters['title'] = mb_strtolower($parameters['title']);

			// encode title
			if ( $encodeTitle ) {
				$parameters['title'] = rawurlencode($parameters['title']);
			}
		}

		$parameters['controller'] = $controller;

		$routeURL = RouteHandler::getInstance()->buildLink($parameters);
		if ( !$isRaw && !empty($url) ) {
			$routeURL .= (strpos($routeURL, '?') === false) ? '?' : '&';
		}

		// encode certain characters
		if ( !empty($url) ) {
			$url = str_replace(['[', ']'], ['%5B', '%5D'], $url);
		}

		$url = BASE_URL.$routeURL.$url;

		// append previously removed anchor
		$url .= $anchor;

		return $url;
	}
}