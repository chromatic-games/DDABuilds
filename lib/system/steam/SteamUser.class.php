<?php

namespace system\steam;

use data\notification\NotificationList;

/**
 * Class SteamUser
 * @package system\steam
 *
 * @property string $steamID
 * @property string $displayName
 * @property array  $avatar
 * @property int    $timeCreated
 * @property int    $lastUpdate
 */
class SteamUser {
	/**
	 * @var null|int
	 */
	protected $unreadNotifications;

	/**
	 * @var array
	 */
	protected $_data;

	public function __construct($steamID, $data = []) {
		if ( $steamID ) {
			// TODO maybe use curl
			$content = file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".STEAM_API_KEY."&steamids=".$steamID);
			$responseData = json_decode($content, true);
			$data['steamID'] = $responseData['response']['players'][0]['steamid'];
			$data['displayName'] = $responseData['response']['players'][0]['personaname'];
			$data['avatar'] = [
				// 'avatar' => $data['response']['players'][0]['avatar'],
				'medium' => $responseData['response']['players'][0]['avatarmedium'],
				// 'full' => $data['response']['players'][0]['avatarfull'],
			];
			$data['timeCreated'] = $responseData['response']['players'][0]['timecreated'];
			$data['lastUpdate'] = time();

			// $profile['communityVisibilityState'] = $data['response']['players'][0]['communityvisibilitystate'];
			// $profile['profileState'] = $data['response']['players'][0]['profilestate'];
			// $profile['profileurl'] = $data['response']['players'][0]['profileurl'];
			// $profile['personastate'] = $data['response']['players'][0]['personastate'];
			// $profile['primaryClanId'] = $data['response']['players'][0]['primaryclanid'];
			/*if ( isset($data['response']['players'][0]['realname']) ) {
				$profile['realname'] = $data['response']['players'][0]['realname'];
			}
			else {
				$profile['realname'] = "Real name not given";
			}*/
		}

		$this->_data = $data;
	}

	public function getUnreadNotifications() {
		if ( $this->unreadNotifications === null ) {
			$this->unreadNotifications = 0;
			$notificationList = new NotificationList();
			$notificationList->getConditionBuilder()->add('steamID = ? AND seen = 0', [$this->steamID]);
			$this->unreadNotifications = $notificationList->countObjects();
		}

		return $this->unreadNotifications;
	}

	public function getData() {
		return $this->_data;
	}

	public function __set($name, $value) {
		$this->_data[$name] = $value;
	}

	public function __get($name) {
		return isset($this->_data[$name]) ? $this->_data[$name] : null;
	}
}