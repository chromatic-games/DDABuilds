<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

// TODO properties
class Map extends AbstractModel {
	protected $table = 'map';

	protected $primaryKey = 'id';

	public $timestamps = false;

	public function scopeFindByName(Builder $builder, string $mapName) {
		return $builder->where('name', '=', $mapName);
	}
}