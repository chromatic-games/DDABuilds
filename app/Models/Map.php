<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $ID
 * @property string $name
 * @property int $units
 * @property int $mapCategoryID
 */
class Map extends AbstractModel
{
	protected $table = 'map';

	protected $primaryKey = 'ID';

	public $timestamps = false;

	public function difficultyUnits(): HasMany
	{
		return $this->hasMany(MapAvailableUnit::class, 'mapID', 'ID');
	}

	public function getPublicPath(): string
	{
		return public_path('assets/images/map/' . $this->name . '.png');
	}
}