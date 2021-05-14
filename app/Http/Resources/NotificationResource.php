<?php

namespace App\Http\Resources;

use App\Laravel\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property-read string      $id
 * @property-read string      $type
 * @property-read string      $notifiable_type
 * @property-read int         $notifiable_id
 * @property-read string      $data
 * @property-read Carbon|null $read_at
 * @property-read Carbon      $created_at
 * @property-read Carbon      $updated_at
 */
class NotificationResource extends JsonResource {
	public function toArray($request) {
		return [
			'id' => $this->id,
			'type' => $this->getType(),
			'created' => $this->created_at->format('Y-m-d H:i:s'),
			'read' => !!$this->read_at,
			'data' => $this->data,
		];
	}

	public function getType() {
		$split = explode('\\', $this->type);

		return lcfirst(substr(array_pop($split), 0, -12)); // -12 = Notification
	}
}
