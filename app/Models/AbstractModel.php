<?php

namespace App\Models;

use App\Laravel\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model {
	public function newEloquentBuilder($query) {
		return new Builder($query);
	}
}