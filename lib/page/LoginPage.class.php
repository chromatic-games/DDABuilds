<?php

namespace page;

use system\Core;
use system\steam\LightOpenID;
use system\steam\SteamUser;

class LoginPage extends AbstractPage {
	public function readParameters() {
		parent::readParameters();

		if ( Core::getUser()->steamID ) {
			throw new \Exception('namedexception -> already logged in');
		}
	}

	public function readData() {
		parent::readData();

		try {
			$openid = new LightOpenID(BASE_URL);

			if ( !$openid->mode ) {
				$openid->identity = 'https://steamcommunity.com/openid';
				header('Location: '.$openid->authUrl());
			}
			elseif ( $openid->mode == 'cancel' ) {
				Core::getTPL()->assign('error', 'User has canceled authentication!');
			}
			elseif ( $openid->validate() ) {
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);

				$_SESSION['steamid'] = $matches[1];
				$_SESSION['steam_profile'] = (new SteamUser($matches[1]))->getData();

				header('Location: /');
				exit;
			}
			else {
				Core::getTPL()->assign('error', 'User is not logged in.');
			}
		} catch ( \Exception $e ) {
			echo $e->getMessage();
		}
	}
}