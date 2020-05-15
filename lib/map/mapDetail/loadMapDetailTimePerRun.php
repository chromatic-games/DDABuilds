<?php
/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 24.04.2017
 * Time: 16:28
 */
$value = '';
if ($create || $isCreator) {
    if (!empty($_GET['load'])) {
        $value = 'value="' . $build->getData('timeperrun') . '"';
    }
    echo '<label>Time Per Run:</label>';
    echo '<input type="text" placeholder="Time Per Run" class="form-control" id="timeperrun" maxlength="20"' . $value . '>';
} else {
    if ($build->getData('timeperrun')) {
        echo '<h4>Time Per Run: <b>' . htmlspecialchars($build->getData('timeperrun')) . '</b></h4>';
    }
}