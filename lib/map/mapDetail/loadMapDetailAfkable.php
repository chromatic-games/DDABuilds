<?php
if ($create || $isCreator) {
    $selected = '';
    if (!empty($_GET['load']) && $build->getData('afkable') === 1) {
        $selected = 'checked="checked"';
    }
    echo '
        <div class="checkbox">
            <label><input type="checkbox" id="afkable" value="1" ' . $selected . '>AFK Able</label>
        </div>';
} else {
    $afkable = 'No';
    if ($build->getData('afkable')) {
        $afkable = 'Yes';
    }
    echo '<h4>AFK Able: <b>' . $afkable . '</b></h4>';
}
