<?php

namespace App\Models;

/**
 * @property-read int $mapID
 * @property-read int $difficultyID
 * @property-read int $units
 */
class MapAvailableUnit extends AbstractModel {
	protected $table = 'map_available_unit';

	public $incrementing = false;

	public $timestamps = false;
}