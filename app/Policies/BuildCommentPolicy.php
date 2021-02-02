<?php

namespace App\Policies;

use App\Models\Build\BuildComment;
use App\Models\SteamUser;

class BuildCommentPolicy {
	public function viewAny(SteamUser $steamUser) {
		return true;
	}

	public function create(SteamUser $steamUser) {
		return !!auth()->id();
	}

	public function like(SteamUser $steamUser, BuildComment $comment) {
		return $comment->steamID !== $steamUser->ID;
	}
}
