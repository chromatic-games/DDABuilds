<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use App\Models\Build;

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
			'streamID' => $this->steamID,
			'buildStatus' => $this->buildStatus,
			'timePerRun' => $this->timePerRun,
			'expPerRun' => $this->expPerRun,
			'gameModeID' => $this->gameModeID,
			'gameModeName' => $this->gameMode->name,
			'difficultyID' => $this->difficultyID,
			'difficultyName' => $this->difficulty->name,
			'mapID' => $this->mapID,
			'mapName' => $this->map->name,
			'views' => $this->views,
			'likes' => $this->likes,
			'comments' => $this->comments,
			'hardcore' => $this->hardcore,
			'afkAble' => $this->afkAble,
			'isDeleted' => $this->isDeleted,
		];
	}
}