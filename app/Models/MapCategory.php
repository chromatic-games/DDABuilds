<?php

namespace App\Models;

class MapCategory extends AbstractModel {
	protected $table = 'map_category';

	protected $primaryKey = 'ID';

	public function maps() {
		return $this->hasMany(Map::class, 'mapCategoryID', 'ID');
	}
}