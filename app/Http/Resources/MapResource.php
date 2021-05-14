<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use App\Models\Map;

/**
 * @mixin Map
 */
class MapResource extends JsonResource {
	public function toArray($request) {
		return [
			'ID' => $this->ID,
			'name' => $this->name,
			'units' => $this->units,
			'difficultyUnits' => $this->whenLoaded('difficultyUnits'),
		];
	}
}
