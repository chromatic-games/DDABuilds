<?php
use system\request\LinkHandler;
?>

<div class="col-md-3 portfolio-item">
    <a href="<?php echo LinkHandler::getInstance()->getLink('Map', ['load' => $map->getID()]); ?>">
        <?php echo $map->getData('name'); ?>
        <img class="img-responsive" src="/assets/images/map/<?php echo str_replace(' ', '_', $map->getData('name')); ?>.png">
    </a>
</div>