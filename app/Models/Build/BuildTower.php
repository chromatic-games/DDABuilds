<?php

namespace App\Models\Build;

use App\Models\Tower;
use Illuminate\Database\Eloquent\Model;

/**
 * @property-read int   $towerID
 * @property-read int   $x
 * @property-read int   $y
 * @property-read int   $rotation
 * @property-read int   $buildWaveID
 * @property-read int   $overrideUnits
 * @property-read Tower $towerInfo
 */
class BuildTower extends Model {
	protected $table = 'build_tower';

	protected $primaryKey = null;

	public $incrementing = false;

	public $timestamps = false;

	protected $fillable = [
		'buildWaveID',
		'towerID',
		'x',
		'y',
		'rotation',
		'overrideUnits',
	];

	public function towerInfo() {
		return $this->hasOne(Tower::class, 'ID', 'towerID');
	}

	public function getPublicPath() {
		$name = $this->towerInfo->name;
		if ( $this->towerInfo->isResizable ) {
			$name .= $this->overrideUnits ? : $this->towerInfo->unitCost;
		}

		return public_path('assets/images/tower/'.$name.'.png');
	}
}