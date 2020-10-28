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
			'author'          => $author,
			'name'            => $this->faker->words($this->faker->numberBetween(1, 3), true),
			'map_id'          => $this->faker->numberBetween(1, 15),
			'difficulty_id'   => $this->faker->numberBetween(1, 4),
			'description'     => $this->faker->sentences($this->faker->numberBetween(10, 100), true),
			'date'            => $this->faker->date('Y-m-d H:i:s'),
			'steam_id'        => $this->faker->steamID,
			'build_status_id' => $this->faker->numberBetween(1, 3),
			'game_mode_id'    => $this->faker->numberBetween(1, 5),
			'hardcore'        => $this->faker->boolean(),
			'afkable'         => $this->faker->boolean(),
		];
	}
}