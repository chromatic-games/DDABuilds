<?php
require_once('global.php');
ob_start();
system\request\RouteHandler::getInstance()->handle();