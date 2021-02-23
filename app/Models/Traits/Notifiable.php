<?php

namespace App\Models\Traits;

use App\Models\DatabaseNotification;
use Illuminate\Notifications\HasDatabaseNotifications;
use Illuminate\Notifications\RoutesNotifications;

/**
 * @property-read DatabaseNotification $unreadNotifications
 */
trait Notifiable {
	use HasDatabaseNotifications, RoutesNotifications;

	public function notifications() {
		return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
	}
}