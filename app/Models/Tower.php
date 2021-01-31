<?php

namespace App\Models;

/**
 * @property-read int    $ID
 * @property-read int    $unitType (0/1 TODO doc)
 * @property-read int    $unitCost
 * @property-read int    $maxUnitCost
 * @property-read int    $manaCost
 * @property-read int    $heroClassID
 * @property-read string $name
 * @property-read int    $isResizable
 * @property-read int    $isRotatable
 *
 * @property-read Hero    $hero
 */
class Tower extends AbstractModel {
	protected $table = 'tower';

	protected $primaryKey = 'ID';

	public function hero() {
		return $this->hasOne(Hero::class, 'ID', 'heroClassID');
	}

	public function getPublicPath() {
		return public_path('assets/images/tower/'.$this->name.'.png');
	}
}