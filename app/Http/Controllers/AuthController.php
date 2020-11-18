<?php

namespace App\Http\Controllers;

use App\Auth\SteamAuth;
use App\Models\SteamUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends AbstractController {
	/** @var SteamAuth */
	public $steamAuth;

	public function __construct(SteamAuth $steamAuth) {
		$this->steamAuth = $steamAuth;
	}

	public function auth(Request $request) {
		if ( $request->query('debug') && app()->environment('local') ) {
			// localhost:8000/api/auth/steam?debug=steamID
			$user = SteamUser::find($request->query('debug'));
			if ( $user ) {
				Auth::login($user, true);
			}

			return redirect('/auth/');
		}

		if ( $this->steamAuth->isValidRequest() ) {
			$steamID = $this->steamAuth->auth();
			if ( $steamID ) {
				$userInfo = $this->steamAuth->getUserInfo();
				$user = SteamUser::find($steamID);

				// create new steam user
				if ( $user === null ) {
					$user = SteamUser::create([
						'ID'          => $steamID,
						'name'        => $userInfo['personaname'],
						'avatar_hash' => $userInfo['avatarhash'],
					]);
				}
				// update steam user
				else {
					$user->update([
						'name'        => $userInfo['personaname'],
						'avatar_hash' => $userInfo['avatarhash'],
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