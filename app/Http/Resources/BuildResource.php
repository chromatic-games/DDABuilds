<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use App\Models\Build;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin Build
 */
class BuildResource extends JsonResource {
	public function toArray($request) {
		return [
			'ID' => $this->ID,
			'author' => $this->author,
			'title' => $this->title,
			'description' => $this->description,
			'date' => $this->date,
			'steamID' => $this->steamID,
			'buildStatus' => $this->buildStatus,
			'timePerRun' => $this->timePerRun,
			'expPerRun' => $this->expPerRun,
			'gameModeID' => $this->gameModeID,
			'gameModeName' => $this->relationLoaded('gameMode') ? $this->gameMode->name : new MissingValue(),
			'difficultyID' => $this->difficultyID,
			'difficultyName' => $this->relationLoaded('difficulty') ? $this->difficulty->name : new MissingValue(),
			'mapID' => $this->mapID,
			'mapName' => $this->relationLoaded('map') ? $this->map->name : new MissingValue(),
			'views' => $this->views,
			'likes' => $this->likes,
			'comments' => $this->comments,
			'hardcore' => $this->hardcore,
			'afkAble' => $this->afkAble,
			'rifted' => $this->rifted,
			'waves' => $this->whenLoaded('waves'),
			'heroStats' => $this->whenLoaded('heroStats'),
			'isDeleted' => $this->isDeleted,
			'likeValue' => $this->relationLoaded('likeValue') ? ($this->likeValue ? $this->likeValue->likeValue : 0) : new MissingValue(),
			'watchStatus' => $this->relationLoaded('watchStatus') ? ($this->watchStatus ? 1 : 0) : new MissingValue(),
		];
	}
}