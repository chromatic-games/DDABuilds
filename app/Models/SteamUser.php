<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SteamUser extends Authenticatable {
	use HasFactory;

	/** @inheritdoc */
	public $timestamps = false;

	/** @inheritdoc */
	protected $table = 'steam_user';

	/** @inheritdoc */
	protected $fillable = [
		'ID',
		'name',
		'avatarHash',
	];

	/** @inheritdoc */
	protected $primaryKey = 'ID';

	/** @inheritdoc */
	public function setRememberToken($value) {
		// do nothing - ignore remember token
	}
}