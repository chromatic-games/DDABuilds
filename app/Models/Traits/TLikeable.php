<?php

namespace App\Models\Traits;

use App\Models\Like;

trait TLikeable {
	public function getLikeObjectType() {
		return lcfirst(array_slice(explode('\\', static::class), -1)[0]);
	}

	public function likeValue() {
		$objectType = $this->getLikeObjectType();

		return $this
			->hasOne(Like::class, 'objectID', $this->getKeyName())
			->where([
				['steamID', auth()->id() ?? '0'],
				['objectType', $objectType],
			]);
	}
}