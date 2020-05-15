<?php

/**
 * Helper method to output debug data for all passed variables,
 * uses `print_r()` for arrays and objects, `var_dump()` otherwise.
 */
function wcfDebug() {
	echo "<pre>";

	$args = func_get_args();
	$length = count($args);
	if ($length === 0) {
		echo "ERROR: No arguments provided.<hr>";
	}
	else {
		for ($i = 0; $i < $length; $i++) {
			$arg = $args[$i];

			echo "<h2>Argument {$i} (" . gettype($arg) . ")</h2>";

			if (is_array($arg) || is_object($arg)) {
				print_r($arg);
			}
			else {
				var_dump($arg);
			}

			echo "<hr>";
		}
	}

	$backtrace = debug_backtrace();

	// output call location to help finding these debug outputs again
	echo "wcfDebug() called in {$backtrace[0]['file']} on line {$backtrace[0]['line']}";

	echo "</pre>";

	exit;
}