<?php

namespace App\Models\Build;

use Illuminate\Database\Eloquent\Model;

class BuildTower extends Model
{
	/** @inheritDoc */
    protected $table = 'build_tower';

	/** @inheritDoc */
    protected $primaryKey = null;

	/** @inheritDoc */
    public $incrementing = false;

	/** @inheritDoc */
    public $timestamps = false;

	/** @inheritDoc */
    protected $fillable = [
    	'buildWaveID',
	    'towerID',
	    'x',
	    'y',
	    'rotation',
	    'overrideUnits'
    ];
}