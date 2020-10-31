<?php

namespace Database\Factories;

use App\Models\Build;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildFactory extends Factory {
	/** @inheritdoc */
	protected $model = Build::class;

	/** @inheritdoc */
	public function definition() {
		$author = substr($this->faker->name(), 0, 20);

		return [
			'author'       => $author,
			'title'         => $this->faker->words($this->faker->numberBetween(1, 3), true),
			'mapID'        => $this->faker->numberBetween(1, 15),
			'difficultyID' => $this->faker->numberBetween(1, 6),
			'description'  => $this->faker->sentences($this->faker->numberBetween(10, 100), true),
			'date'         => $this->faker->date('Y-m-d H:i:s'),
			'steamID'      => $this->faker->steamID,
			'buildStatus'  => $this->faker->numberBetween(1, 3),
			'gameModeID'   => $this->faker->numberBetween(1, 5),
			'hardcore'     => $this->faker->boolean(),
			'afkAble'      => $this->faker->boolean(),
			'views'        => $this->faker->numberBetween(500, 500000),
		];
	}
}