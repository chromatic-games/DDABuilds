<?php

namespace data\tower;

use data\DatabaseObject;
use system\util\StringUtil;

/**
 * @package data\tower
 *
 * @property-read int    $id
 * @property-read int    $mu
 * @property-read int    $unitcost
 * @property-read int    $manacost
 * @property-read int    $fk_class
 * @property-read string $name
 */
class Tower extends DatabaseObject {
	protected static $databaseTableName = 'towers';

	protected static $databaseTableIndexName = 'id';

	public function getImage() {
		return '/assets/images/tower/'.strtolower(str_replace(' ', '_', $this->name)).'.png';
	}

	public function getHtml($placed = null, $dummy = true) {
		$wave = 0;
		$menu = $style = '';

		if ( $placed ) {
			$dummy = false;
			$style = ' style="position:absolute;top:'.$placed['y'].'px;left:'.$placed['x'].'px;"';
			$wave = $placed['fk_buildwave'];
		}

		if ( $this->fk_class !== 4 && $this->fk_class !== 3 /*&& $tower->fk_class !== ????*/ ) { // TODO replace with database column
			$menu = '<div class="menu"><i class="fa fa-repeat"></i></div>';
		}

		return '<div class="tower-container'.($dummy ? ' dummy' : '').'" data-tower-id="'.$this->getObjectID().'" data-class="'.$this->fk_class.'"
	        data-du="'.$this->unitcost.'" data-mana="'.$this->manacost.'" data-wave="'.$wave.'"'.$style.'>
	            <!--<div class="pool">-->
	                <img class="tower" src="'.$this->getImage().'" title="'.StringUtil::encodeHTML($this->name).'" />
	            <!--</div>-->
                '.$menu.'
	        </div>';
	}
}