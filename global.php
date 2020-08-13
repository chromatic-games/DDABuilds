<?php
require_once('./config.inc.php');

// defines
define('MAIN_DIR', __DIR__.'/');
define('LIB_DIR', MAIN_DIR.'lib/');
define('COOKIE_PREFIX', 'ddabuild_');
define('APPLICATION_START', microtime(true));

require_once(MAIN_DIR.'lib/system/Core.class.php');
new system\Core();