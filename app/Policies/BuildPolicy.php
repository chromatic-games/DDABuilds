<?php

namespace App\Policies;

use App\Models\Build;
use App\Models\SteamUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildPolicy {
	use HandlesAuthorization;

	public function viewAny(SteamUser $steamUser) {
		exit;
	}

	public function view(?SteamUser $steamUser, Build $build) {
		if ( $build->isDeleted ) {
			return false;
		}

		if ( $build->buildStatus !== Build::STATUS_PRIVATE ) {
			return true;
		}

		if ( $steamUser === null || $steamUser->ID != $build->steamID ) { // TODO save $build->steamID as bigint?
			return false;
		}

		return true;
	}

	public function create(SteamUser $steamUser) {
		exit;
	}

	public function update(SteamUser $steamUser, Build $build) {
		exit;
	}

	public function delete(SteamUser $steamUser, Build $build) {
		exit;
	}

	public function like(SteamUser $steamUser, Build $build) {
		if ( !$this->view($steamUser, $build) ) {
			return false;
		}

		return $steamUser->ID !== $build->steamID;
	}
}