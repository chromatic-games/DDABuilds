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
<ol class="buildList mapSelection">';
		/** @var \data\map\Map $map */
		foreach ($maps as $map) {
			$link = LinkHandler::getInstance()->getLink('BuildAdd', ['object' => $map]);

			echo '<li>
				<div class="buildBox">
                    <div class="box128">
                        <div class="buildDataContainer">
                            <h4 class="buildSubject">
                                <a href="'.$link.'">'.$this->escapeHtml($map->name).'</a>
                            </h4>
                            <a href="'.$link.'"><img class="img-responsive" style="height: 200px;margin: 15px auto auto;" src="'.$map->getImage().'"></a>
                        </div>
                    </div>
                    <div class="buildFiller"></div>
                </div>
            </li>';
		}
		echo '</ol></div>';
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
        $('.top').UItoTop({autoLinkText: '<i class="fa fa-chevron-up"></i>'});
    });
</script>