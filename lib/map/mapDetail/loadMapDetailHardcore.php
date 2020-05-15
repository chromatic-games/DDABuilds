<?php
if ($create || $isCreator) {
    if (!empty($_GET['load']) && $build->getData('hardcore')) {
        $selected = 'checked="checked"';
    }

    echo '
        <div class="checkbox">
            <label><input type="checkbox" id="hardcore" value="1" ' . $selected . '>Hardcore</label>
        </div>';
} else {
    if (!$build->getData('purestrategy')) {
        $hardcore = 'No';
        if ($build->getData('hardcore')) {
            $hardcore = 'Yes';
        }
        echo '<h4>Hardcore: <b>' . $hardcore . '</b></h4>';
    }
}