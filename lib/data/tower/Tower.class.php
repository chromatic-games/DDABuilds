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
 * @property-read int    $maxUnitCost
 * @property-read int    $manacost
 * @property-read int    $fk_class
 * @property-read string $name
 */
class Tower extends DatabaseObject {
	protected static $databaseTableName = 'towers';

	protected static $databaseTableIndexName = 'id';

	public function getImage($customDU = 0) {
		$name = $this->name;
		if ( $customDU ) {
			$name .= '_'.$customDU;
		}

		return '/assets/images/tower/'.strtolower(str_replace(' ', '_', $name)).'.png';
	}

	/**
	 * is tower resizeable? like series ev towers
	 *
	 * @return bool
	 */
	public function isResizeable() {
		return $this->maxUnitCost && $this->maxUnitCost > $this->unitcost;
	}

	public function getHtml($placed = null, $dummy = true, $hideMenu = false, $customDU = 0) {
		$wave = 0;
		$menu = $style = '';

		if ( $placed ) {
			$dummy = false;
			$style .= ' style="position:absolute;top:'.$placed['y'].'px;left:'.$placed['x'].'px;transform: rotate('.$placed['rotation'].'deg);transform-origin: 50% 50% 0;"';
			$wave = $placed['fk_buildwave'];
		}

		if ( $this->fk_class !== 4 && $this->fk_class !== 3 /*&& $tower->fk_class !== ????*/ ) { // TODO replace with database column
			$menu = '<i class="fa fa-repeat"></i>';
			if ( $this->fk_class === 5 ) {
				$menu = '<i class="fa fa-minus du-decrease"></i> '.$menu;
				$menu .= ' <i class="fa fa-plus du-increase"></i>';
			}
		}

		if ( $hideMenu ) {
			$menu = '';
		}

		if ( $this->mu ) {
			$style .= ' data-mu';
		}

		if ( $this->isResizeable() ) {
			$style .= ' data-min-unit="'.$this->unitcost.'" data-max-unit="'.$this->maxUnitCost.'"';
		}

		$currentUnitCost = $customDU ? : $this->unitcost;

		return '<div class="tower-container'.($dummy ? ' dummy' : '').'" data-tower-id="'.$this->getObjectID().'" data-class="'.$this->fk_class.'"
	        data-du="'.$currentUnitCost.'" data-mana="'.$this->manacost.'" data-wave="'.$wave.'"'.$style.'>
                <img class="tower" src="'.$this->getImage($customDU).'" title="'.StringUtil::encodeHTML($this->name).($this->isResizeable() && !$dummy ? ' ('.$currentUnitCost.')' : '').'" />
                '.($menu ? '<div class="menu">'.$menu.'</div>' : '').'
	        </div>';
	}
}