<?php

namespace App\Models\Like;

/**
 * @property-read string $steamID
 */
interface ILikeableModel {
	public function likeValue();
}