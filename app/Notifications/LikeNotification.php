<?php

namespace App\Notifications;

use App\Models\Like;
use App\Models\Like\AbstractLike;
use App\Models\SteamUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LikeNotification extends Notification {
	use Queueable;

	/** @var AbstractLike */
	protected $likeModel;

	/** @var Like */
	protected $likeObject;

	public function __construct(AbstractLike $like, Like $likeObject) {
		$this->likeModel = $like;
		$this->likeObject = $likeObject;
	}

	public function getLikeObject() : Like {
		return $this->likeObject;
	}

	public function via($notifiable) {
		return ['database'];
	}

	public function toDatabase($notifiable) {
		return array_merge($this->likeModel->getNotificationData(), [
			'likeValue' => $this->getLikeObject()->likeValue,
			'context' => $this->likeModel->objectType,
			'user' => SteamUser::query()->find($this->getLikeObject()->steamID)->first()->getNotificationData(),
		]);
	}
}
