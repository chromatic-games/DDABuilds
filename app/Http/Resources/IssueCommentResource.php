<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use App\Models\IssueComment;
use Illuminate\Http\Resources\MissingValue;

/** @mixin IssueComment */
class IssueCommentResource extends JsonResource {
	public function toArray($request) {
		return [
			'ID' => $this->commentID,
			'steamID' => $this->steamID,
			'time' => $this->time,
			'description' => $this->description,
			'steamName' => $this->relationLoaded('user') ? $this->user->name : new MissingValue(),
		];
	}
}
