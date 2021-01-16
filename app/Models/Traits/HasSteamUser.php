<?php

namespace App\Models\Traits;

use App\Models\AbstractModel;
use App\Models\SteamUser;

/**
 * @mixin AbstractModel
 */
trait HasSteamUser {
	public function user() {
		return $this->hasOne(SteamUser::class, 'ID', 'steamID')->select(['ID', 'name']);
	}
}