<?php
if ($create || $isCreator) {
    include LIB_DIR.'list/loadDifficultySelect.php';
} else {
    $buildDifficulty = new Difficulty();
    $buildDifficulty->setID($build->getData('difficulty'));
    $buildDifficulty->load();
    echo '<h4>Difficulty: <b>' . $buildDifficulty->getData('name') . '</b></h4>';
}