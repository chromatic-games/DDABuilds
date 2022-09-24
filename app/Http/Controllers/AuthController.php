<?php

namespace App\Http\Controllers;

use App\Auth\SteamAuth;
use App\Models\SteamUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AuthController extends AbstractController {
	public SteamAuth $steamAuth;

	public function __construct(SteamAuth $steamAuth) {
		$this->steamAuth = $steamAuth;
	}

	public function auth(Request $request) {
		if ( $request->query('debug') && app()->isLocal() ) {
			// /api/auth/steam?debug=steamID
			/** @var SteamUser $user */
			$user = SteamUser::query()->find($request->query('debug'));
			if ( $user ) {
				Auth::login($user, true);
			}

			return redirect('/auth/');
		}

		if ( $this->steamAuth->isValidRequest() ) {
			$steamID = $this->steamAuth->auth();
			if ( $steamID ) {
				$userInfo = $this->steamAuth->getUserInfo();
				/** @var SteamUser $user */
				$user = SteamUser::query()->updateOrCreate([
					'ID' => $steamID,
				], [
					'name' => $userInfo['personaname'],
					'avatarHash' => $userInfo['avatarhash'],
				]);

				Auth::login($user, true);

				return redirect('/auth/');
			}
		}

		throw new BadRequestHttpException();
	}

	public function logout() : JsonResponse {
		Auth::logout();

		return response()->json(['status' => 'OK']);
	}
}