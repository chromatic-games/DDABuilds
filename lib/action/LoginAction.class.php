<?php

namespace action;

use system\Core;
use system\exception\NamedUserException;
use system\exception\PermissionDeniedException;
use system\steam\LightOpenID;
use system\steam\SteamUser;
use system\util\HeaderUtil;

class LoginAction extends AbstractAction {
	/** @inheritDoc */
	public function readParameters() {
		parent::readParameters();

		if ( Core::getUser()->steamID ) {
			throw new PermissionDeniedException();
		}
	}

	/** @inheritDoc */
	public function execute() {
		parent::execute();

		/*$_SESSION['_steamid'] = '76561198054589426';
		$_SESSION['_steam_profile'] = (new SteamUser('76561198054589426'))->getData();
		HeaderUtil::redirect('/');
		exit;*/

		try {
			$openid = new LightOpenID(BASE_URL);

			if ( !$openid->mode ) {
				$openid->identity = 'https://steamcommunity.com/openid';
				header('Location: '.$openid->authUrl());
			}
			elseif ( $openid->mode == 'cancel' ) {
				throw new NamedUserException('User has canceled authentication!');
			}
			elseif ( $openid->validate() ) {
				$id = $openid->identity;
				$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);

				$_SESSION['_steamid'] = $matches[1];
				$_SESSION['_steam_profile'] = (new SteamUser($matches[1]))->getData();

				HeaderUtil::redirect('/');
				exit;
			}
			else {
				throw new NamedUserException('User is not logged in.');
			}
		} catch ( \Exception $e ) {
			throw new NamedUserException($e->getMessage());
		}
	}
}