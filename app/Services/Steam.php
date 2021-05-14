<?php

namespace App\Services;

class Steam {
	public $steamConfig;

	public function __construct() {
		$this->steamConfig = config('services')['steam'];
	}

	public function getUserInfo($steamID) {
		$url = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$this->steamConfig['apiKey'].'&steamids='.$steamID);
		$data = json_decode($url, true);
		if ( !isset($data['response']['players'][0]) || !is_array($data['response']['players'][0]) ) {
			return null;
		}

		return $data['response']['players'][0];
	}
}