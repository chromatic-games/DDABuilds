<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * represent a map
 *
 * @method Map|null findByName(string $mapName)
 *
 * @package App\Models
 */
class Map extends Model {
	/** @inheritdoc */
	protected $table = 'map';

	/** @inheritdoc */
	protected $primaryKey = 'id';

	/** @inheritdoc */
	public $timestamps = false;

	/**
	 * find a map by name
	 *
	 * @param Builder $builder
	 * @param string  $mapName
	 *
	 * @return null|Builder|Model|object
	 */
	public function scopeFindByName(Builder $builder, string $mapName) {
		return $builder->where('name', '=', $mapName);
	}
}