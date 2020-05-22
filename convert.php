<?php

use data\build\wave\BuildWave;
use data\build\wave\BuildWaveList;
use system\Core;

require_once('global.php');

$buildWaves = new BuildWaveList();
$buildWaves->readObjects();
$buildIDs = [];
$buildWaveIDs = [];
/** @var BuildWave $buildWave */
foreach ( $buildWaves as $buildWave ) {
	if ( !in_array($buildWave->fk_build, $buildIDs) ) {
		$buildIDs[] = $buildWave->fk_build;
		$buildWaveIDs[$buildWave->fk_build] = [];
	}

	$buildWaveIDs[$buildWave->fk_build][] = $buildWave->getObjectID();
}

$updatePlaced = Core::getDB()->prepareStatement('UPDATE placed SET fk_buildwave = ? WHERE fk_build = ? AND fk_buildwave = ?');
foreach ( $buildWaveIDs as $buildID => $waveIDs ) {
	foreach ( $waveIDs as $idx => $waveID ) {
		$updatePlaced->execute([
			$idx + 1,
			$buildID,
			$waveID,
		]);
	}
}

if ( unlink('./convert.php') ) {
	wcfDebug('convert done!');
}
else {
	wcfDebug('convert done delete the convert.php');
}
