<?php
if (!HIDETHIS) {
    if ($create || $isCreator) {
        if (!empty($_GET['load']) && $build->getData('mixmode')) {
            $selected = 'checked="checked"';
        }
        echo '
            <div class="checkbox">
                <label><input type="checkbox" id="mixmode" value="1" ' . $selected . '>Mix Mode</label>
            </div>';
    } else {
        if ($build->getData('survival')) {
            $mixmode = 'No';
            if ($build->getData('mixmode')) {
                $mixmode = 'Yes';
            }
            echo '<h4>Mix Mode: <b>' . $mixmode . '</b></h4>';
        }
    }
}