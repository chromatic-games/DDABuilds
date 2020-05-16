<?php

namespace system\util;

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
}