<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 24.04.2017
 * Time: 18:55
 */

use system\Core;

$sort = [];
$order = 'DESC';
$by = 'id';
$sort['fk_user'] = Core::getUser()->steamID;

if ( !empty($var = Parameter::_GET('order')) ) {
	if ( $var === 'DESC' || $var === 'ASC' ) {
		$order = $var;
	}
}
if ( !empty($var = Parameter::_GET('by')) ) {
	if ( $var === 'map' ) {
		$by = 'mapname';
	}
}
if ( !empty($var = Parameter::_GET('by')) ) {
	if ( $var === 'name' || $var === 'difficulty' || $var === 'views' || $var === 'date' || $var === 'author' || $var === 'votes' ) {
		$by = $var;
	}
}

$pages = Builds::getPageNumbers($sort);
$site = intval(Parameter::_GET('pageNo'));
if ( $site < 1 || $site > $pages ) {
	$site = 1;
}
$builds = Builds::getBuildsFor($sort, $site, $order, $by);