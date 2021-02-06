<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use App\Models\Build\BuildComment;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin BuildComment
 */
class BuildCommentResource extends JsonResource {
	public function toArray($request) {
		$userLoaded = $this->relationLoaded('user') && $this->user;

		return [
			'ID' => $this->ID,
			'steamID' => $this->steamID,
			'date' => $this->date,
			'description' => $this->description,
			'steamName' => $userLoaded ? $this->user->name : new MissingValue(),
			'avatarHash' => $userLoaded ? $this->user->avatarHash : new MissingValue(),
			'likes' => $this->likes,
			'dislikes' => $this->dislikes,
			'likeValue' => $this->relationLoaded('likeValue') ? ($this->likeValue ? $this->likeValue->likeValue : 0) : new MissingValue(),
		];
	}
}