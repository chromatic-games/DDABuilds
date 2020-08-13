<?php

namespace {

	use system\Core;

	// spl auto loader
	spl_autoload_register(function ($className) {
		$classPath = LIB_DIR.implode('/', explode('\\', $className)).'.class.php';

		if ( file_exists($classPath) ) {
			require_once($classPath);
		}
	});

	// set shutdown function
	register_shutdown_function([Core::class, 'destruct']);
	// set exception handler
	set_exception_handler([Core::class, 'handleException']);
	// set php error handler
	set_error_handler([Core::class, 'handleError'], E_ALL);

	/**
	 * Helper method to output debug data for all passed variables,
	 * uses `print_r()` for arrays and objects, `var_dump()` otherwise.
	 */
	function wcfDebug() {
		echo "<pre>";

		$args = func_get_args();
		$length = count($args);
		if ( $length === 0 ) {
			echo "ERROR: No arguments provided.<hr>";
		}
		else {
			for ( $i = 0;$i < $length;$i++ ) {
				$arg = $args[$i];

				echo "<h2>Argument {$i} (".gettype($arg).")</h2>";

				if ( is_array($arg) || is_object($arg) ) {
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

	define('TIME_NOW', time());
}

namespace functions\exception {

	use system\Core;
	use system\exception\IExtraInformationException;
	use system\util\StringUtil;

	/**
	 * @param \Throwable|\Exception $e
	 * @param string                $logFile
	 *
	 * @return string exceptionID
	 */
	function logThrowable($e, &$logFile = null) {
		if ( $logFile === null ) {
			$logFile = MAIN_DIR.'logs/'.gmdate('Y-m-d', TIME_NOW).'.txt';
		}
		touch($logFile);

		// don't forget to update ExceptionLogViewPage, when changing the log file format
		$message = gmdate('r', TIME_NOW)."\n".
		           'Message: '.str_replace("\n", ' ', $e->getMessage())."\n".
		           'PHP version: '.phpversion()."\n".
		           'Request URI: '.(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '')."\n".
		           'Referrer: '.(isset($_SERVER['HTTP_REFERER']) ? str_replace("\n", ' ', $_SERVER['HTTP_REFERER']) : '')."\n".
		           'User Agent: '.(isset($_SERVER['HTTP_USER_AGENT']) ? str_replace("\n", ' ', $_SERVER['HTTP_USER_AGENT']) : '')."\n".
		           'Peak Memory Usage: '.memory_get_peak_usage().'/'.ini_get('memory_limit')."\n";
		$prev = $e;

		do {
			$trace = array_map(function ($item) {
				if ( !empty($item['args']) ) {
					$item['args'] = array_map(function ($item) {
						switch ( gettype($item) ) {
							case 'object':
								return get_class($item);
							case 'array':
								return array_map(function () {
									return '[redacted]';
								}, $item);
							case 'resource':
								return 'resource('.get_resource_type($item).')';
							default:
								return $item;
						}
					}, $item['args']);
				}

				return $item;
			}, sanitizeStacktrace($prev));

			$message .= "======\n".
			            'Error Class: '.get_class($prev)."\n".
			            'Error Message: '.str_replace("\n", ' ', $prev->getMessage())."\n".
			            'Error Code: '.intval($prev->getCode())."\n".
			            'File: '.str_replace("\n", ' ', $prev->getFile()).' ('.$prev->getLine().')'."\n".
			            'Extra Information: '.($prev instanceof IExtraInformationException ? base64_encode(serialize($prev->getExtraInformation())) : '-')."\n".
			            'Stack Trace: '.json_encode($trace)."\n";
		} while ( $prev = $prev->getPrevious() );

		// calculate Exception-ID
		$exceptionID = sha1($message);
		$entry = '<<<<<<<<'.$exceptionID."<<<<\n".$message."<<<<\n\n";

		file_put_contents($logFile, $entry, FILE_APPEND);

		// let the Exception know it has been logged
		if ( method_exists($e, 'finalizeLog') && is_callable([$e, 'finalizeLog']) ) {
			$e->finalizeLog($exceptionID, $logFile);
		}

		return $exceptionID;
	}

	/**
	 * @param \Throwable|\Exception $e
	 */
	function printThrowable($e) {
		$exceptionID = logThrowable($e);

		Core::getTPL()->assign([
			'exception'   => $e,
			'exceptionID' => $exceptionID,
		]);
		Core::getTPL()->display('fatalException');
		exit;
	}

	function sanitizePath($path) {
		if ( DEBUG_MODE ) {
			return $path;
		}

		return str_replace(StringUtil::unifyDirSeparator(MAIN_DIR), '', StringUtil::unifyDirSeparator($path));
	}

	/**
	 * Returns the stack trace of the given Throwable with sensitive information removed.
	 *
	 * @param \Throwable|\Exception $e
	 *
	 * @return mixed[]
	 */
	function sanitizeStacktrace($e) {
		return array_map(function ($item) {
			if ( !isset($item['file']) ) {
				$item['file'] = '[internal function]';
			}
			if ( !isset($item['line']) ) {
				$item['line'] = '?';
			}
			if ( !isset($item['class']) ) {
				$item['class'] = '';
			}
			if ( !isset($item['type']) ) {
				$item['type'] = '';
			}
			if ( !isset($item['args']) ) {
				$item['args'] = [];
			}

			$item['file'] = sanitizePath($item['file']);

			return $item;
		}, $e->getTrace());
	}
}