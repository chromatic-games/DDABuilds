<?php

namespace system\util;

use system\request\RouteHandler;

class HeaderUtil {
	/**
	 * Redirects the user agent to given location.
	 *
	 * @param string  $location
	 * @param boolean $sendStatusCode
	 * @param boolean $temporaryRedirect
	 */
	public static function redirect($location, $sendStatusCode = false, $temporaryRedirect = true) {
		if ( $sendStatusCode ) {
			if ( $temporaryRedirect ) {
				@header('HTTP/1.1 307 Temporary Redirect');
			}
			else {
				@header('HTTP/1.1 301 Moved Permanently');
			}
		}

		header('Location: '.$location);
	}

	/**
	 * set cookie with header
	 *
	 * @param string  $name
	 * @param string  $value
	 * @param integer $expire
	 */
	public static function setCookie($name, $value = '', $expire = 0) {
		@header('Set-Cookie: '.rawurlencode(COOKIE_PREFIX.$name).'='.rawurlencode($value).($expire ? '; expires='.gmdate('D, d-M-Y H:i:s', $expire).' GMT; max-age='.($expire - TIME_NOW) : '').'; path=/'.(RouteHandler::secureConnection() ? '; secure' : '').'; HttpOnly', false);
	}
}