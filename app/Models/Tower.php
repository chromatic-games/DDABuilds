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
 */
class Tower extends ABstractModel {
	protected $table = 'tower';

	protected $primaryKey = 'ID';
}