<?php

namespace App\Models;

/**
 * @property-read int    $ID
 * @property-read string $name
 * @property-read int    $isHero
 * @property-read int    $isDisabled
 */
class Hero extends AbstractModel {
	protected $table = 'hero';

	protected $primaryKey = 'ID';

	public function towers() {
		return $this->hasMany(Tower::class, 'heroClassID');
	}
}