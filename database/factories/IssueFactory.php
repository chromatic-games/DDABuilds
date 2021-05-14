<?php

namespace Database\Factories;

use App\Models\Issue;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueFactory extends Factory {
	/** @inheritDoc */
	protected $model = Issue::class;

	/** @inheritDoc */
	public function definition() {
		return [
			'steamID'     => $this->faker->steamID,
			'time'        => $this->faker->unixTime(),
			'title'       => $this->faker->words($this->faker->numberBetween(3, 7), true),
			'description' => $this->faker->sentences($this->faker->numberBetween(10, 100), true),
		];
	}
}