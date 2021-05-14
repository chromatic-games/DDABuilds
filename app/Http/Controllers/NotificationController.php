<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Build\BuildComment;
use App\Models\DatabaseNotification;
use App\Models\SteamUser;
use App\Notifications\BuildCommentNotification;

class NotificationController extends AbstractController {
	public function debug() {
		/** @var BuildComment $buildComment */
		$buildComment = BuildComment::query()->first();
		for ( $i = 0;$i < 20;$i++ ) {
			$buildComment->build->user->notify(new BuildCommentNotification($buildComment));
		}

		return 'OK';
	}

	public function index() {
		return NotificationResource::collection(
			request()->user()->notifications()->simplePaginate()
		);
	}

	public function markAsRead(string $notificationID) {
		/**
		 * @var SteamUser $user
		 * @var DatabaseNotification $notification
		 */
		$user = auth()->user();
		$notification = $user->unreadNotifications->where('id', $notificationID);
		$notification->markAsRead();

		return response()->noContent();
	}

	public function markAllAsRead() {
		/** @var SteamUser $user */
		$user = auth()->user();
		$user->unreadNotifications()->update(['read_at' => now()]);

		return response()->noContent();
	}
}