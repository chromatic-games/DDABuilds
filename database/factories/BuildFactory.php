<?php

namespace Database\Factories;

use App\Models\Build;
use App\Models\Difficulty;
use App\Models\GameMode;
use App\Models\Map;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildFactory extends Factory {
	protected $model = Build::class;

	public function definition() {
		return [
			'author'       => substr($this->faker->name(), 0, 20),
			'title'        => $this->faker->words($this->faker->numberBetween(1, 3), true),
			'description'  => $this->faker->sentences($this->faker->numberBetween(10, 100), true),
			'date'         => $this->faker->unixTime(),
			'steamID'      => $this->faker->steamID,
			'mapID'        => $this->maps()->random()->ID,
			'difficultyID' => $this->difficulties()->random()->ID,
			'gameModeID'   => $this->gameModes()->random()->ID,
			'buildStatus'  => $this->faker->numberBetween(1, 3),
			'hardcore'     => $this->faker->boolean(),
			'afkAble'      => $this->faker->boolean(),
			'views'        => $this->faker->numberBetween(500, 500000),
		];
	}

	/**
	 * @return Map[]|Collection
	 */
	private function maps() {
		static $maps;
		if ( $maps === null ) {
			$maps = Map::all();
		}

		return $maps;
	}

	/**
	 * @return Difficulty[]|Collection
	 */
	private function difficulties() {
		static $difficulties;
		if ( $difficulties === null ) {
			$difficulties = Difficulty::all();
		}

		return $difficulties;
	}

	/**
	 * @return GameMode[]|Collection
	 */
	private function gameModes() {
		static $gameModes;
		if ( $gameModes === null ) {
			$gameModes = GameMode::all();
		}

		return $gameModes;
	}
}