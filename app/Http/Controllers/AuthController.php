<?php

namespace App\Http\Controllers;

use App\Auth\SteamAuth;
use App\Models\SteamUser;
use App\Policies\IssuePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends AbstractController {
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
						'ID' => $steamID,
						'name' => $userInfo['personaname'],
						'avatar_hash' => $userInfo['avatarhash'],
					]);
				}
				// update steam user
				else {
					$user->update([
						'name' => $userInfo['personaname'],
						'avatar_hash' => $userInfo['avatarhash'],
					]);
				}

				Auth::login($user, true);

				return redirect('/auth/');
			}
		}

		throw new BadRequestHttpException();
	}

	public function authInfo(IssuePolicy $issuePolicy) {
		return array_merge(Auth::user()->toArray(), [
			'isMaintainer' => $issuePolicy->isMaintainer(auth()->user()),
		]);
	}

	public function logout() {
		Auth::logout();

		return response()->json(['status' => 'OK']);
	}
}