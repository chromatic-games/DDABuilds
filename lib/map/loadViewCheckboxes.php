<?php
$oDBH = Database::getInstance();
$heroes = Heroes::getAllHeroes($oDBH);

$highlightChecked = '';
if (isset($_SESSION['highlightTower']) && $_SESSION['highlightTower']) {
    $highlightChecked = 'checked="checked"';
}

echo '<div class="panel panel-default">';
printf ('<div class="panel-heading">Disable Tower | Highlight Tower (Not Working for Chrome): <input type="checkbox" id="highlightTower" %s></div>', $highlightChecked);
echo '<div class="panel-body">';
foreach ($heroes as $hero) {
    if ($hero->getID() == 10) {
        continue;
    }
    $heroToLower = strtolower($hero->getData('name'));
    echo '
        <label>
        <input type="checkbox" class="disableckbx" value="' . $heroToLower . '" />
        <img src="/assets/images/heroes/' . $heroToLower . '.png" title="' . $hero->getData('name') . '" class="disablecheckbox"/>
        </label>';
}

echo '</div> </div>';
