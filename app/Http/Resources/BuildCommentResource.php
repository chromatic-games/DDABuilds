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
		return [
			'ID' => $this->ID,
			'steamID' => $this->steamID,
			'date' => $this->date,
			'description' => $this->description,
			'steamName' => $this->relationLoaded('user') ? $this->user->name : new MissingValue(),
			'avatarHash' => $this->relationLoaded('user') ? $this->user->avatarHash : new MissingValue(),
			'likes' => $this->likes,
			'dislikes' => $this->dislikes,
		];
	}
}