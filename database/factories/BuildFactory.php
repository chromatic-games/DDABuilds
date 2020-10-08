<?php

namespace Database\Factories;

use App\Models\Build;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuildFactory extends Factory {
	/**
	 * The name of the factory's corresponding model.
	 *
	 * @var string
	 */
	protected $model = Build::class;

	/**
	 * Define the model's default state.
	 *
	 * @return array
	 */
	public function definition() {
		$author = substr($this->faker->name(), 0, 20);

		return [
			'author'         => $author,
			'name'           => $this->faker->words($this->faker->numberBetween(1, 3), true),
			'map'            => $this->faker->numberBetween(1, 15),
			'difficulty'     => $this->faker->numberBetween(1, 4),
			'description'    => $this->faker->sentences($this->faker->numberBetween(10, 100), true),
			'date'           => $this->faker->date('Y-m-d H:i:s'),
			'fk_user'        => $this->faker->steamID,
			'fk_buildstatus' => $this->faker->numberBetween(1, 3),
			'gamemodeID'     => $this->faker->numberBetween(1, 5),
			'hardcore'       => $this->faker->boolean(),
			'afkable'        => $this->faker->boolean(),
		];
	}
}