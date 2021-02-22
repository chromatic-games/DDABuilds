<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Build\BuildComment;
use App\Notifications\BuildCommentNotification;

class NotificationController extends AbstractController {
	public function debug() {
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
}