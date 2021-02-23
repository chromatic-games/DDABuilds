<?php

namespace App\Listeners;

use App\Notifications\LikeNotification;
use Illuminate\Notifications\Events\NotificationSent;

class LikeNotificationListener {
	public function handle(NotificationSent $event) {
		if ( !($event->notification instanceof LikeNotification) ) {
			return;
		}

		/** @var LikeNotification $likeNotification */
		$likeNotification = $event->notification;
		$likeNotification->getLikeObject()->update([
			'notificationID' => $likeNotification->id,
		]);
	}
}