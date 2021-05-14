<?php

namespace App\Models;

use App\Laravel\Builder;
use Illuminate\Notifications\DatabaseNotification as BaseDatabaseNotification;

class DatabaseNotification extends BaseDatabaseNotification {
	public function newEloquentBuilder($query) {
		return new Builder($query);
	}
}