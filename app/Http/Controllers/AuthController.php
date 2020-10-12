<?php

namespace App\Http\Controllers;

use App\Auth\SteamAuth;
use App\Models\SteamUser;
use Illuminate\Support\Facades\Auth;

class AuthController extends AbstractController {
	/** @var SteamAuth */
	public $steamAuth;

	public function __construct(SteamAuth $steamAuth) {
		$this->steamAuth = $steamAuth;
	}

	public function auth() {
		if ( $this->steamAuth->isValidRequest() ) {
			$steamID = $this->steamAuth->auth();
			if ( $steamID ) {
				$userInfo = $this->steamAuth->getUserInfo();
				$user = SteamUser::find($steamID);

				// create new steam user
				if ( $user === null ) {
					$user = SteamUser::create([
						'steamID'    => $steamID,
						'name'       => $userInfo['personaname'],
						'avatarHash' => $userInfo['avatarhash'],
					]);
				}
				// update steam user
				else {
					$user->update([
						'name'       => $userInfo['personaname'],
						'avatarHash' => $userInfo['avatarhash'],
					]);
				}

				Auth::login($user, true);

				return redirect('/auth/');
			}
		}

		return response()->apiBadRequest();
	}

	public function authInfo() {
		return Auth::user();
	}

	public function logout() {
		Auth::logout();

		return response()->json(['status' => 'OK']);
	}
}