<?php

namespace App\Policies;

use App\Models\Build;
use App\Models\SteamUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildPolicy {
	use HandlesAuthorization;

	public function viewAny(SteamUser $steamUser) {
		return true;
	}

	public function view(?SteamUser $steamUser, Build $build) {
		if ( $build->isDeleted ) {
			return false;
		}

		if ( $build->buildStatus !== Build::STATUS_PRIVATE ) {
			return true;
		}

		if ( $steamUser === null || $steamUser->ID !== $build->steamID ) {
			return false;
		}

		return true;
	}

	public function create(SteamUser $steamUser) {
		exit;
	}

	public function update(SteamUser $steamUser, Build $build) {
		if ( $build->isDeleted ) {
			return false;
		}

		return $steamUser->ID === $build->steamID;
	}

	public function delete(SteamUser $steamUser, Build $build) {
		return $this->update($steamUser, $build);
	}

	public function like(SteamUser $steamUser, Build $build) {
		if ( !$this->view($steamUser, $build) ) {
			return false;
		}

		return $steamUser->ID !== $build->steamID;
	}

	public function watch(SteamUser $steamUser, Build $build) {
		return $this->like($steamUser, $build);
	}
}