<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use App\Models\Hero;

/**
 * @mixin Hero
 */
class HeroResource extends JsonResource {
	public function toArray($request) {
		return [
			'ID' => $this->ID,
			'name' => $this->name,
			'isHero' => $this->isHero,
			'towers' => $this->whenLoaded('towers'),
		];
	}
}