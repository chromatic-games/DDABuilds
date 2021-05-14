<?php

namespace Database\Factories;

use App\Models\IssueComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueCommentFactory extends Factory {
	protected $model = IssueComment::class;

	public function definition() {
		return [
			'steamID' => $this->faker->steamID,
			'time' => $this->faker->unixTime(),
			'description' => $this->faker->sentences($this->faker->numberBetween(10, 100), true),
		];
	}
}
