<?php
if ($create || $isCreator) {
    include 'loadBuildStatusSelect.php';
} else {
    $buildStatus = new BuildStatus();
    $buildStatus->setID($build->getData('fk_buildstatus'));
    $buildStatus->load();
    echo '<h4>Build Status: <b>' . $buildStatus->getData('name') . '</b></h4>';
}