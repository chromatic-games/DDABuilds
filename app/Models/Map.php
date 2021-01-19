<?php

namespace App\Models;

/**
 * @property-read int    $ID
 * @property-read string $name
 * @property-read int    $units
 * @property-read int    $mapCategoryID
 *
 * @property-read string $image
 */
class Map extends AbstractModel {
	protected $table = 'map';

	protected $primaryKey = 'ID';

	public $timestamps = false;

	public function difficultyUnits() {
		return $this->hasMany(MapAvailableUnit::class, 'mapID', 'ID');
	}
}