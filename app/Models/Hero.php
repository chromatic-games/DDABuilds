<?php

namespace App\Models;

// TODO properties
class Hero extends AbstractModel {
	protected $table = 'hero';

	protected $primaryKey = 'ID';

	public function towers() {
		return $this->hasMany(Tower::class, 'heroClassID');
	}
}