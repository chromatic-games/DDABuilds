<?php

namespace App\Models\Build;

use App\Models\AbstractModel;
use App\Models\Like\ILikeableModel;
use App\Models\Traits\HasSteamUser;
use App\Models\Traits\TLikeable;

class BuildComment extends AbstractModel implements ILikeableModel {
	use HasSteamUser;
	use TLikeable;

	protected $table = 'build_comment';

	protected $primaryKey = 'ID';

	public $timestamps = false;

	protected $fillable = [
		'description',
		'steamID',
		'date',
	];

	public function getLikeObjectType() {
		return 'comment';
	}
}
