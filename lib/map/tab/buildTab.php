<!--
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 20.04.2017
 * Time: 12:36
-->

<?php

use system\Core;

$size = 'col-lg-9';
if (isset($_GET['creator']) && isset($_GET['customwave']) && isset($_GET['map'])) {
    $create = true;
    $isCreator = true;
    $buildwave = $_GET['customwave'];
    $mapID = $_GET['map'];
    $map = new Map();
    $map->setID($mapID);
    $map->load();
    $mapName = $map->getData('name');
}else if (isset($_GET['thumbnail'])) {
    $size = 'col-lg-4';
    $buildID = $_GET['thumbnail'];
    $build = new Build();
    $build->setID($buildID);
    $build->load();
    $buildwave = 0;
    $map = new Map();
    $map->setID($build->getData('map'));
    $map->load();
    $mapName = $map->getData('name');
    echo '<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/chakratos.css" rel="stylesheet">
    <link href="/assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="/assets/css/full-width-pics.css" rel="stylesheet">';
} else if(!isset($buildwave)) {
    http_response_code(404);
    exit();
}
?>


<div class="<?php echo $size; ?>">
    <div class="canvas">
        <img class="ddmap"
             src="<?php echo "/assets/images/map/" . str_replace(' ', '_', $mapName); ?>.png">
        <?php
        include(dirname(__FILE__)."/../loadPlaced.php");
        ?>
    </div>
</div>
<?php
if (isset($_GET['thumbnail'])) {
    exit();
}
?>
<div class="col-lg-3">
    <?php
    if ($create || $isCreator) {
        include(dirname(__FILE__)."/../loadPools.php");
    } else {
        include(dirname(__FILE__)."/../loadViewCheckboxes.php");
        if (Core::getUser()->steamID) {
            include(dirname(__FILE__)."/../loadVoteTool.php");
        }
    }

    if ($buildwave === 0) {
        include(dirname(__FILE__)."/../loadMapBuildDetails.php");
    }  else if (!$create && !$isCreator) {
        include(dirname(__FILE__)."/../loadMapBuildDetails.php");
    }

    $viewerButton = '';
    $deleteButton = '';
    if ($create || $isCreator) {
        if ($buildwave !== 0) {
            $deleteButton = '
                <div class="col-md-6 text-center">
                    <button type="button" wave="' . $buildwave . '" class="btn btn-danger deletewave">Delete Wave</button>
                </div>'
            ;
            echo $deleteButton;
        }
    }
    if ($isCreator && $buildwave === 0) {
        $viewerButton = '
            <div class="col-md-4 text-center">
                <button type="button" id="viewer" class="btn btn-info">Viewer Mode</button>
            </div>';
    }
    if ($isCreator && $buildwave === 0) {
        $deleteButton = '
            <div class="col-md-4 text-center">
                <button type="button" id="delete" class="btn btn-danger">Delete Build</button>
            </div>';
    }
    if ($create || $isCreator) {
        if ($buildwave === 0) {
            echo '
                <div class="row">
                    <div class="col-md-4 text-center">
                        <button type="button" id="save" class="btn btn-primary">Save</button>
                    </div>
                    ' . $viewerButton . $deleteButton .
                '</div><br>'
            ;
        }
    }
    ?>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if ($buildwave == 0) {
                include(dirname(__FILE__)."/../loadDescriptionTool.php");
            } else if (!$create && !$isCreator) {
                include(dirname(__FILE__)."/../loadDescriptionTool.php");
            }
            ?>
        </div>
    </div>
</div>
