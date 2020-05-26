<?php

namespace action;

use system\Core;
use system\exception\NamedUserException;
use system\exception\PermissionDeniedException;
use system\steam\LightOpenID;
use system\steam\Steam;
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

				$steamData = Steam::getInstance()->getPlayerSummary($matches[1]);
				$statement = Core::getDB()->prepareStatement('INSERT INTO steam_user (steamID, name, avatarHash) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = ?, avatarHash = ?');
				$statement->execute([
					$matches[1],
					$steamData['personaname'],
					$steamData['avatarhash'],
					$steamData['personaname'],
					$steamData['avatarhash'],
				]);;

				$_SESSION['__steamid'] = $matches[1];

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