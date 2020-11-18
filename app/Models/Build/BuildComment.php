<?php

namespace App\Models\Build;

use Illuminate\Database\Eloquent\Model;

class BuildComment extends Model
{
	/** @inheritDoc */
	protected $table = 'build_comment';

	/** @inheritDoc */
	protected $primaryKey = 'ID';
}
