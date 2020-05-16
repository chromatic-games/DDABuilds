<div class="container">
    <div class="list-group text-center">
        <?php
        /** @var \data\map\category\MapCategory $category */

        use system\request\LinkHandler;

        foreach ( $this->categories as $category) {
            echo '<a href="#category-'.$category->getObjectID().'" class="list-group-item list-group-item-action"> '.$this->escapeHtml($category->name).' </a>';
        }
        ?>
    </div>

	<?php
	foreach ( $this->maps as $categoryId => $maps) {
		echo '
		<div class="container" id="category-'.$categoryId.'">
<h1 class="page-header">'.$this->escapeHtml($this->categories[$categoryId]->name).' <small></small></h1>
<div class="row">';
		/** @var \data\map\Map $map */
		foreach ($maps as $map) {
			echo '<div class="col-md-3 portfolio-item">
    <a href="'.LinkHandler::getInstance()->getLink('BuildAddForm', ['object' => $map]).'">'.$this->escapeHtml($map->name).'
        <img class="img-responsive" src="'.$map->getImage().'">
    </a>
</div>';
		}
		echo '</div></div>';
	}
	?>
</div>
<style>
	a:focus, a:hover {
		opacity: 0.8;
	}
</style>
<script>
    $(document).ready(function () {
        $('.top').UItoTop();
    });
</script>