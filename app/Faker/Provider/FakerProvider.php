<?php

namespace App\Faker\Provider;

use Faker\Generator;
use Faker\Provider\Base;

/**
 * Class FakerProvider
 * @package Database\Factories
 * @mixin Generator
 */
class FakerProvider extends Base {
	/**
	 * generate a random steam ID
	 *
	 * @return int
	 */
	public function steamID() {
		return $this->generator->randomElement([
			76561198054589426, // derpierre65
			76561198080938830, // dragongun100
		]);
	}
}