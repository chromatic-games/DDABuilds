<?php

namespace system\util;

class ArrayUtil {
	/**
	 * Applies intval() to all elements of the given array.
	 *
	 * @param array|string $array
	 *
	 * @return array|string
	 */
	public static function toIntegerArray($array) {
		if ( !is_array($array) ) {
			return intval($array);
		}
		else {
			foreach ( $array as $key => $val ) {
				$array[$key] = self::toIntegerArray($val);
			}

			return $array;
		}
	}
}