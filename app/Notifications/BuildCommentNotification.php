<?php

namespace App\Notifications;

use App\Models\Build;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BuildCommentNotification extends Notification {
	use Queueable;

	protected $buildComment;

	public function __construct(Build\BuildComment $buildComment) {
		$this->buildComment = $buildComment;
	}

	public function via($notifiable) {
		return ['database'];
	}

	public function toDatabase($notifiable) {
		return [
			'build' => $this->buildComment->build->getNotificationData(),
			'user' => $this->buildComment->user->getNotificationData(),
		];
	}
}