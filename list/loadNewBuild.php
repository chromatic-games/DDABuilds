<?php
$map = new Map();
$map->setID($build->getData('map'));
$map->load();
$difficulty = new Difficulty();
$difficulty->setID($build->getData('difficulty'));
$difficulty->load();
$votes = Votes::getBuildVoting($build->getID(), $oDBH);
$color = "";
if ($votes > 0) {
    $color = ' style="background:rgba(0, 255, 0, .4)"';
} else if ($votes < 0) {
    $color = ' style="background:rgba(255, 0, 0, .4)"';
}
$id = $build->getID();
$name = htmlspecialchars($build->getData('name'));
$mapname = $map->getData('name');
$difficulty = $difficulty->getData('name');
$date = date('d F Y', strtotime($build->getData('date')));
$author = $build->getData('author');
$views = $build->getData('views');
$thumbnail = '/assets/images/thumbnails/' . $build->getID() . '.png';
//Utility::varDump($thumbnail);
if (!file_exists(DOCROOT . $thumbnail)) {
    $thumbnail = 'http://via.placeholder.com/262x262?text=Placeholder';
}

?>

<div class="col-md-4">
    <div class="row">
        <h3><a href="map.php?load=<?php echo $id?>"><?php echo $name;?></h3>
    </div>
    <div class="row">
        <div class="col-md-7">
            <img align="left" class="img-responsive" style="height: 200px" src="<?php echo $thumbnail; ?>">
        </div>
        <div class="col-md-5 align-middle">
            <?php
            echo '<h4><p>' . $mapname . '</p>';
            echo '<p>' . $difficulty . '<p>';
            echo '<p><small>Rating:</small> ' . '<span' . $color . '>' . $votes . '</span></p>';
            echo '<p><small>Views:</small> ' . $views . '</p>';
            echo '<p>' . $date . '</p>';
            echo '<p>' . $author . '</p></h4></a>';
            ?>
        </div>
    </div>
</div>
