<div class="form-group">
    <label for="difficultyselect">Difficulty:</label>
    <select class="form-control" id="difficultyselect">
        <?php
        $difficulties = Difficulties::getAllDifficulties();
        foreach ($difficulties as $difficulty) {
            $difficultyId = $difficulty->getID();
            $difficultyName = $difficulty->getData('name');
            $selected = '';
            if (!empty($_GET['load']) && $difficultyId == $build->getData('difficulty')) {
                $selected = 'selected="selected"';
            }
            echo '<option ' . $selected . ' value="' . $difficultyId . '">' . $difficultyName . '</option>';
        }
        ?>
    </select>
</div>