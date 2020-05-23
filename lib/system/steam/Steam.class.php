<?php

namespace system\steam;

use data\steam\user\SteamUser as SteamUserObject;
use system\cache\runtime\SteamUserRuntimeCache;
use system\SingletonFactory;

class Steam extends SingletonFactory {
	/** @var string */
	const BUTTON_STYLE_RECTANGLE = '01';

	/** @var string */
	const BUTTON_STYLE_SQUARE = '02';

	/** @var int count the steam requests */
	protected $requests = 0;

	/** @var array run time cache for player summaries */
	protected $_runtimeCache = [];

	public function getPlayerSummary($steamID) {
		if ( !isset($this->_runtimeCache[$steamID]) ) {
			$this->requests++;
			$url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".STEAM_API_KEY."&steamids=".$steamID);
			$data = json_decode($url, true);
			if ( is_array($data) && isset($data['response']['players']) ) {
				foreach ( $data['response']['players'] as $player ) {
					$this->_runtimeCache[$player['steamid']] = $player;
				}
			}
		}

		return isset($this->_runtimeCache[$steamID]) ? $this->_runtimeCache[$steamID] : null;
	}

	/**
	 * @param string $steamID
	 * @param int    $size
	 *
	 * @return null|string
	 */
	public function getAvatar($steamID, $size = SteamUserObject::AVATAR_MEDIUM) {
		$playerSummary = $this->getPlayerSummary($steamID);
		if ( $playerSummary === null ) {
			return null;
		}

		$hashAdditional = '';
		if ( $size === SteamUserObject::AVATAR_BIG ) {
			$hashAdditional = '_full';
		}
		elseif ( $size === SteamUserObject::AVATAR_MEDIUM ) {
			$hashAdditional = '_medium';
		}

		return 'https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/'.substr($playerSummary['avatarhash'], 0, 2).'/'.$playerSummary['avatarhash'].$hashAdditional.'.jpg';
	}

	/**
	 * @deprecated
	 */
	public function getAvatarMedium($steamID) {
		$playerSummary = $this->getPlayerSummary($steamID);
		if ( $playerSummary === null ) {
			return null;
		}

		return $playerSummary['avatarmedium'];
	}

	/**
	 * @param int $steamID
	 *
	 * @return string
	 * @deprecated
	 */
	public function getDisplayName($steamID) {
		// if found a steam user in the database, use from database not from api call
		$steamUser = SteamUserRuntimeCache::getInstance()->getObject($steamID);
		if ( $steamUser !== null ) {
			return $steamUser->name;
		}

		$playerSummary = $this->getPlayerSummary($steamID);
		if ( $playerSummary === null ) {
			return null;
		}

		return $playerSummary['personaname'];
	}

	/**
	 * @return int
	 */
	public function getRequests() {
		return $this->requests;
	}

	/**
	 * get the login button in given style
	 *
	 * @param string $style
	 *
	 * @return string
	 */
	public function getLoginButton($style = self::BUTTON_STYLE_SQUARE) {
		return '<img alt="Login" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_'.$style.'.png">';
	}
}