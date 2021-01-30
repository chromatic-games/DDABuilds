<?php

namespace App\Models\Build;

use App\Models\AbstractModel;

class BuildWatch extends AbstractModel {
	protected $table = 'build_watch';

	public $incrementing = false;

	protected $primaryKey = null;
}