<?php
require_once 'config.php';
include "header.php";
?>

<style>
    a:focus, a:hover {
        opacity: 0.8;
    }
</style>
<body>
<?php
include "navbar.php";
?>
<div class="container">
    <div class="list-group text-center">
        <?php
        $oDBH = Database::getInstance();
        $mapCategories = MapCategories::getAllMapCategories($oDBH);

        foreach ($mapCategories as $mapCategory) {
            echo '<a href="#' . $mapCategory->getID() . '" class="list-group-item list-group-item-action category' . $mapCategory->getID() . '"> ' . $mapCategory->getData('name') . ' </a>';
        }
        ?>
    </div>
</div>
<?php

foreach ($mapCategories as $mapCategory) {
    include 'maps/loadMapsCategory.php';
    $mapsForCategory = Maps::getMapsWithCategory($mapCategory->getID(), $oDBH);
    $i = 0;
    foreach ($mapsForCategory as $map) {
        if ($i == 0) {
            echo '<div class="row">';
        }

        include 'maps/loadMapsItem.php';

        $i++;
        if ($i == 4) {
            $i = 0;
            echo '</div>';
        }
    }
    if ($i != 0) {
        echo '</div>';
    }
    echo '</div>';
}

?>

</body>
<script>
    $(document).ready(function () {
        $('.top').UItoTop();
    });
</script>
</html>
