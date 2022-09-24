<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $ID
 * @property int $unitType (0 = du, 1 = mu)
 * @property int $unitCost
 * @property int $maxUnitCost
 * @property int $manaCost
 * @property int $heroClassID
 * @property string $name
 * @property int $isResizable
 * @property int $isRotatable
 *
 * @property Hero $hero
 */
class Tower extends AbstractModel
{
	protected $table = 'tower';

	protected $primaryKey = 'ID';

	public $timestamps = false;

	protected $guarded = [];

	public function hero(): HasOne
	{
		return $this->hasOne(Hero::class, 'ID', 'heroClassID');
	}

	public function getPublicPath(): string
	{
		return public_path('assets/images/tower/' . $this->name . '.png');
	}
}