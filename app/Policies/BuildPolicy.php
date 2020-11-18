<?php

namespace App\Policies;

use App\Models\Build;
use App\Models\SteamUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildPolicy {
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view any models.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 *
	 * @return mixed
	 */
	public function viewAny(SteamUser $steamUser) {
		exit;
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 * @param \App\Models\Build     $build
	 *
	 * @return mixed
	 */
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

	/**
	 * Determine whether the user can create models.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 *
	 * @return mixed
	 */
	public function create(SteamUser $steamUser) {
		exit;
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 * @param \App\Models\Build     $build
	 *
	 * @return mixed
	 */
	public function update(SteamUser $steamUser, Build $build) {
		exit;
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 * @param \App\Models\Build     $build
	 *
	 * @return mixed
	 */
	public function delete(SteamUser $steamUser, Build $build) {
		exit;
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 * @param \App\Models\Build     $build
	 *
	 * @return mixed
	 */
	public function restore(SteamUser $steamUser, Build $build) {
		exit;
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param \App\Models\SteamUser $steamUser
	 * @param \App\Models\Build     $build
	 *
	 * @return mixed
	 */
	public function forceDelete(SteamUser $steamUser, Build $build) {
		exit;
	}
}
