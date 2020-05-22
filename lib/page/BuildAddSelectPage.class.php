<?php

namespace page;

use data\map\category\MapCategoryList;
use data\map\Map;
use data\map\MapList;
use system\Core;

class BuildAddSelectPage extends AbstractPage {
	/** @inheritDoc */
	public $loginRequired = true;

	public $pageTitle = 'Create Build';

	/** @inheritDoc */
	public function assignVariables() {
		parent::assignVariables();

		$mapsByCategory = [];
		$maps = new MapList();
		$categories = new MapCategoryList();
		$categories->readObjects();
		$maps->readObjects();

		/** @var Map $map */
		foreach ( $maps as $map ) {
			$mapsByCategory[$map->fk_mapcategory][] = $map;
		}

		Core::getTPL()->assign([
			'maps'       => $mapsByCategory,
			'categories' => $categories->getObjects(),
		]);
	}
}