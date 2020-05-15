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
        $value = 'value="' . $build->getData('expperrun') . '"';
    }
    echo '<label>XP Per Run:</label>';
    echo '<input type="text" placeholder="XP Per Run" class="form-control" id="expperrun" maxlength="20"' . $value . '>';
} else {
    if ($build->getData('expperrun')) {
        echo '<h4>XP Per Run: <b>' . htmlspecialchars($build->getData('expperrun')) . '</b></h4>';
    }
}