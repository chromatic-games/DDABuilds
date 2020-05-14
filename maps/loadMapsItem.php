<div class="col-md-3 portfolio-item">
    <a href="../map.php?id=<?php echo $map->getID(); ?>">
        <?php echo $map->getData('name'); ?>
        <img class="img-responsive" src="images/map/<?php echo str_replace(' ', '_', $map->getData('name')); ?>.png">
    </a>
</div>