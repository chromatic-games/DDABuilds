<?php
$oDBH = Database::getInstance();
$sort = array();

$order = 'DESC';
$by = 'id';

$sort['fk_buildstatus'] = 1;

if (!empty($var = Parameter::_GET('bname'))) {
    $sort['name'] = $var;
}
if (!empty($var = Parameter::_GET('map')) && is_numeric($var)) {
    $sort['map'] = $var;
}
if (!empty($var = Parameter::_GET('difficulty')) && is_numeric($var)) {
    $sort['difficulty'] = $var;
}
if (!empty($var = Parameter::_GET('rating')) && is_numeric($var)) {
    $sort['rating'] = $var;
}
if (!empty($var = Parameter::_GET('author'))) {
    $sort['author'] = $var;
}
if (!empty($var = Parameter::_GET('order'))) {
    if ($var === 'DESC' || $var === 'ASC') {
        $order = $var;
    }
}
if (!empty($var = Parameter::_GET('by'))) {
    if ($var === 'map') {
        $by = 'mapname';
    }
}
if (!empty($var = Parameter::_GET('by'))) {
    if ($var === 'name' || $var === 'difficulty' || $var === 'views' || $var === 'date' || $var === 'author' || $var === 'votes') {
        $by = $var;
    }
}

$pages = Builds::getPageNumbers($sort, $oDBH);
$site = intval(Parameter::_GET('page'));
if ($site < 1 || $site > $pages) {
    $site = 1;
}
$builds = Builds::getBuildsFor($sort, $site, $oDBH, $order, $by);
