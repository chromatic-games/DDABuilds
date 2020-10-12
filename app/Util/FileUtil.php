<?php

namespace App\Util;

class FileUtil {
	public static function formatFilesize($byte, $precision = 2) {
		$symbol = 'Byte';
		if ( $byte >= 1000 ) {
			$byte /= 1000;
			$symbol = 'kB';
		}
		if ( $byte >= 1000 ) {
			$byte /= 1000;
			$symbol = 'MB';
		}
		if ( $byte >= 1000 ) {
			$byte /= 1000;
			$symbol = 'GB';
		}
		if ( $byte >= 1000 ) {
			$byte /= 1000;
			$symbol = 'TB';
		}

		return round($byte, $precision).' '.$symbol;
	}
}