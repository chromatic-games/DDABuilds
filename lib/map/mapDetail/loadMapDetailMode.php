<?php
if ($create || $isCreator) {
    include 'loadBuildModeButtons.php';
} else {
    $mode = '';
    if ($build->getData('campaign')) {
        $mode = 'Campaign';
    } else if ($build->getData('survival')) {
        $mode = 'Survival';
    } else if ($build->getData('challenge')) {
        $mode = 'Challenge';
    } else if ($build->getData('purestrategy')) {
        $mode = 'Pure Strategy';
    } else if ($build->getData('mixmode')) {
        $mode = 'Mix Mode';
    }
    echo '<h4>Game Mode: <b>' . $mode . '</b></h4>';
}