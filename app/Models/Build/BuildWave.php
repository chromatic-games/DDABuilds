<?php

namespace App\Models\Build;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BuildWave extends Model {
	/** @inheritDoc */
	protected $table = 'build_wave';

	/** @inheritDoc */
	public $timestamps = false;

	/** @inheritDoc */
	protected $fillable = ['name'];

	/** @inheritDoc */
	protected $primaryKey = 'waveID';

	/**
	 * get all towers from this wave
	 *
	 * @return HasMany
	 */
	public function towers() {
		return $this->hasMany(BuildTower::class, 'buildWaveID', 'waveID');
	}
}
