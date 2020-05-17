<?php

namespace data\build\stats;

use data\DatabaseObject;

/**
 * @package data\build\stats
 *
 * @property-read int $buildID
 * @property-read int $classID
 * @property-read int $hp
 * @property-read int $damage
 * @property-read int $rate
 * @property-read int $range
 */
class BuildStats extends DatabaseObject {
	public function getStats() {
		return [
			'hp'     => $this->hp,
			'damage' => $this->damage,
			'rate'   => $this->rate,
			'range'  => $this->range,
		];
	}
}