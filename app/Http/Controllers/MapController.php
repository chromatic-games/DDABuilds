<?php

namespace App\Http\Controllers;

use App\Http\Resources\HeroResource;
use App\Http\Resources\MapResource;
use App\Models\Difficulty;
use App\Models\GameMode;
use App\Models\Hero;
use App\Models\Map;
use App\Models\MapCategory;
use App\Models\Tower;

class MapController extends AbstractController {
	public function index() {
		return MapCategory::with('maps')->get();
	}

	public function editor(Map $map) {
		$map->load('difficultyUnits');

		return [
			'map' => new MapResource($map),
			'heros' => HeroResource::collection(Hero::with('towers')->get()),
			'towers' => Tower::all(),
			'difficulties' => Difficulty::all(),
			'gameModes' => GameMode::all(),
		];
	}
}