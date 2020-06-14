<?php

namespace action;

use data\session\Session;
use data\session\SessionAction;
use system\Core;
use system\exception\NamedUserException;
use system\exception\PermissionDeniedException;
use system\steam\LightOpenID;
use system\steam\Steam;
use system\util\HeaderUtil;
use system\util\StringUtil;

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

				$steamID = $matches[1];
				$steamData = Steam::getInstance()->getPlayerSummary($steamID);
				$statement = Core::getDB()->prepareStatement('INSERT INTO steam_user (steamID, name, avatarHash) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE name = ?, avatarHash = ?');
				$statement->execute([
					$steamID,
					$steamData['personaname'],
					$steamData['avatarhash'],
					$steamData['personaname'],
					$steamData['avatarhash'],
				]);;

				$session = Session::getSessionBySteamID($steamID);
				if ( !$session->getObjectID() ) {
					$sessionID = StringUtil::getUUID();
					$action = new SessionAction([], 'create', [
						'data' => [
							'sessionID' => $sessionID,
							'steamID'   => $steamID,
							'expires'   => TIME_NOW + 86400 * 30,
						],
					]);
					$action->executeAction();
				}
				else {
					$sessionID = $session->getObjectID();
					if ( $session->isExpired() ) {
						$sessionID = StringUtil::getUUID();
					}

					$action = new SessionAction([$session], 'update', [
						'data' => [
							'sessionID' => $sessionID,
							'expires'   => TIME_NOW + 86400 * 30,
						],
					]);
					$action->executeAction();
				}

				SessionAction::deleteExpiredSessions();
				HeaderUtil::setCookie('sessionID', $sessionID);
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