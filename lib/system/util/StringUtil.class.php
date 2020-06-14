<?php

namespace system\util;

class StringUtil {
	public static function removeInsecureHtml($html) {
		$html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
		$html = preg_replace('#<style(.*?)>(.*?)</style>#is', '', $html);
		$html = preg_replace('#<link(.*?)>(.*?)</link>#is', '', $html);

		return $html;
	}

	/**
	 * Formats a numeric.
	 *
	 * @param number $numeric
	 *
	 * @return string
	 */
	public static function formatNumeric($numeric) {
		if ( is_int($numeric) ) {
			return self::formatInteger($numeric);
		}
		elseif ( is_float($numeric) || floatval($numeric) - (float) intval($numeric) ) {
			return self::formatDouble($numeric);
		}
		else {
			return self::formatInteger(intval($numeric));
		}
	}

	/**
	 * Formats an integer.
	 *
	 * @param integer $integer
	 *
	 * @return string
	 */
	public static function formatInteger($integer) {
		$integer = self::addThousandsSeparator($integer);

		// format minus
		$integer = self::formatNegative($integer);

		return $integer;
	}

	/**
	 * Formats a double.
	 *
	 * @param double  $double
	 * @param integer $maxDecimals
	 *
	 * @return    string
	 */
	public static function formatDouble($double, $maxDecimals = 0) {
		// round
		$double = round($double, ($maxDecimals > 0 ? $maxDecimals : 2));

		// consider as integer, if no decimal places found
		if ( !$maxDecimals && preg_match('~^(-?\d+)(?:\.(?:0*|00[0-4]\d*))?$~', $double, $match) ) {
			return self::formatInteger($match[1]);
		}

		// remove last 0
		if ( $maxDecimals < 2 && substr($double, -1) == '0' ) {
			$double = substr($double, 0, -1);
		}

		// replace decimal point
		$double = str_replace('.', '.', $double);

		// add thousands separator
		$double = self::addThousandsSeparator($double);

		// format minus
		$double = self::formatNegative($double);

		return $double;
	}

	/**
	 * Adds thousands separators to a given number.
	 *
	 * @param mixed $number
	 *
	 * @return string
	 */
	public static function addThousandsSeparator($number) {
		if ( $number >= 1000 || $number <= -1000 ) {
			$number = preg_replace('~(?<=\d)(?=(\d{3})+(?!\d))~', ',', $number);
		}

		return $number;
	}

	/**
	 * Replaces the MINUS-HYPHEN with the MINUS SIGN.
	 *
	 * @param mixed $number
	 *
	 * @return string
	 */
	public static function formatNegative($number) {
		return str_replace('-', "\xE2\x88\x92", $number);
	}

	public static function encodeHTML($string) {
		return @htmlspecialchars($string, ENT_COMPAT, 'UTF-8');
	}

	/**
	 * Unifies windows and unix directory separators.
	 *
	 * @param string $path
	 *
	 * @return string
	 */
	public static function unifyDirSeparator($path) {
		$path = str_replace('\\\\', '/', $path);
		$path = str_replace('\\', '/', $path);

		return $path;
	}

	/**
	 * Removes Unicode whitespace characters from the beginning
	 * and ending of the given string.
	 *
	 * @param string $text
	 *
	 * @return string
	 */
	public static function trim($text) {
		// These regular expressions use character properties
		// to find characters defined as space in the unicode
		// specification.
		// Do not merge the expressions, they are separated for
		// performance reasons.
		$text = preg_replace('/^[\p{Zs}\s]+/u', '', $text);
		$text = preg_replace('/[\p{Zs}\s]+$/u', '', $text);

		return $text;
	}

	/**
	 * Creates an UUID.
	 *
	 * @return string
	 */
	public static function getUUID() {
		return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}
}