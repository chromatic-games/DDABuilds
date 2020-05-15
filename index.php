<?php

use system\Core;
use system\request\RouteHandler;

require_once 'config.inc.php';

// defines
define('MAIN_DIR', __DIR__.'/');
define('LIB_DIR', MAIN_DIR.'lib/');

// start session
session_start();

// spl auto loader
spl_autoload_register(function ($className) {
	if ( file_exists(LIB_DIR.'classes/'.$className.'.php') ) {
		if ( DEBUG_MODE ) {
			trigger_error('old class '.$className.' loaded');
		}
		require_once(LIB_DIR.'classes/'.$className.'.php');
	}
	else {
		$classPath = LIB_DIR.implode('/', explode('\\', $className)).'.class.php';

		if ( file_exists($classPath) ) {
			require_once($classPath);
		}
	}
});

// set shutdown function
register_shutdown_function([Core::class, 'destruct']);
// set exception handler
set_exception_handler([Core::class, 'handleException']);
// set php error handler
set_error_handler([Core::class, 'handleError'], E_ALL);

// initialize the core
new Core();
RouteHandler::getInstance()->handle();