<?php

namespace App\Models\Build;

use App\Models\Traits\HasSteamUser;
use Illuminate\Database\Eloquent\Model;

class BuildComment extends Model {
	use HasSteamUser;

	protected $table = 'build_comment';

	protected $primaryKey = 'ID';

	public $timestamps = false;

	protected $fillable = [
		'description',
		'steamID',
		'date',
	];
}
