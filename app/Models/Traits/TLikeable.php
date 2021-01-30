<?php

namespace App\Models\Traits;

use App\Models\Like;

trait TLikeable {
	public function likeValue() {
		$objectType = lcfirst(array_slice(explode('\\', static::class), -1)[0]);

		return $this
			->hasOne(Like::class, 'objectID', $this->getKeyName())
			->where([
				['steamID', auth()->id() ?? '0'],
				['objectType', $objectType],
			]);
	}
}