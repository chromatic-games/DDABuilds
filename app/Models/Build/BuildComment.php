<?php

namespace App\Models\Build;

use App\Models\AbstractModel;
use App\Models\Build;
use App\Models\Like\ILikeableModel;
use App\Models\Traits\HasSteamUser;
use App\Models\Traits\TLikeable;

/**
 * @property-read int $ID
 * @property-read int $steamID
 * @property-read string $description
 * @property-read int $buildID
 * @property-read int $likes
 * @property-read int $dislikes
 * @property-read int $date
 * @property-read Build $build
 */
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

	public function build() {
		return $this->hasOne(Build::class, 'ID', 'buildID');
	}
}
