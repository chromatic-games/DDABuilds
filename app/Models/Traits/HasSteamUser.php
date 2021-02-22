<?php

namespace App\Models\Traits;

use App\Models\AbstractModel;
use App\Models\SteamUser;

/**
 * @mixin AbstractModel
 *
 * @property-read SteamUser $user
 */
trait HasSteamUser {
	public function user() {
		return $this->hasOne(SteamUser::class, 'ID', 'steamID');
	}
}