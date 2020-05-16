<?php

namespace system\steam;

use system\SingletonFactory;

class Steam extends SingletonFactory {
	const BUTTON_STYLE_RECTANGLE = '01';

	const BUTTON_STYLE_SQUARE    = '02';

	protected $_runtimeCache = [];

	public function getPlayerSummary($steamID) {
		if ( !isset($this->_runtimeCache[$steamID]) ) {
			$url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".STEAM_API_KEY."&steamids=".$steamID);
			$data = json_decode($url, true);
			$this->_runtimeCache[$steamID] = $data;
		}

		return $this->_runtimeCache[$steamID];
	}

	public function getAvatarMedium($steamID) {
		return $this->getPlayerSummary($steamID)['response']['players'][0]['avatarmedium'];
	}

	/**
	 * @param int $steamID
	 *
	 * @return string
	 */
	public function getDisplayName($steamID) {
		return $this->getPlayerSummary($steamID)['response']['players'][0]['personaname'];
	}

	public function getLoginButton($style = self::BUTTON_STYLE_SQUARE) {
		return '<img alt="Login" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_'.$style.'.png">';
	}
}