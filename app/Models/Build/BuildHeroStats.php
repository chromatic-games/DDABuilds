<?php

namespace App\Models\Build;

use App\Models\AbstractModel;

class BuildHeroStats extends AbstractModel {
	protected $table = 'build_stats';

	protected $primaryKey = null;

	public $timestamps = false;

	public $incrementing = false;

	protected $fillable = [
		'buildID',
		'heroID',
		'hp',
		'damage',
		'range',
		'rate',
	];
}