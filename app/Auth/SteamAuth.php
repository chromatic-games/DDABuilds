<?php

namespace App\Auth;

use Illuminate\Http\Request;

class SteamAuth {
	/** @var Request */
	public $request;

	/** @var int|string */
	public $steamID;

	/** @var array */
	public $steamConfig = [];

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request) {
		$this->request = $request;
		$this->steamConfig = config('services')['steam'];
	}

	/**
	 * is the request a valid steam auth request?
	 *
	 * @return bool
	 */
	public function isValidRequest() {
		return $this->request->has('openid_sig') && $this->request->has('openid_signed') && $this->request->has('openid_assoc_handle');
	}

	/**
	 * get steam player information
	 *
	 * @return null|array
	 */
	public function getUserInfo() {
		if ( !$this->steamID ) {
			return null;
		}

		$url = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key='.$this->steamConfig['apiKey'].'&steamids='.$this->steamID);
		$data = json_decode($url, true);
		if ( !is_array($data['response']['players'][0]) ) {
			return null;
		}

		return $data['response']['players'][0];
	}

	/**
	 * validate the current request authentication
	 *
	 * @return null|int|string
	 */
	public function auth() {
		if ( !$this->isValidRequest() ) {
			return null;
		}

		$params = [
			'openid.assoc_handle' => $_GET['openid_assoc_handle'],
			'openid.signed'       => $_GET['openid_signed'],
			'openid.sig'          => $_GET['openid_sig'],
			'openid.ns'           => 'http://specs.openid.net/auth/2.0',
			'openid.mode'         => 'check_authentication',
		];

		$signed = explode(',', $_GET['openid_signed']);
		foreach ( $signed as $item ) {
			$val = $_GET['openid_'.str_replace('.', '_', $item)];
			$params['openid.'.$item] = stripslashes($val);
		}

		$data = http_build_query($params);
		$context = stream_context_create([
			'http' => [
				'method'  => 'POST',
				'header'  => implode("\r\n", [
					'Accept-language: en',
					'Content-type: application/x-www-form-urlencoded',
					'Content-Length: '.strlen($data),
				]),
				'content' => $data,
			],
		]);

		$result = file_get_contents('https://steamcommunity.com/openid/login', false, $context);
		if ( preg_match('#is_valid\\s*:\\s*true#i', $result) !== 1 ) {
			return null;
		}

		preg_match('#^https://steamcommunity.com/openid/id/([0-9]{17,25})#', $_GET['openid_claimed_id'], $matches);
		$this->steamID = is_numeric($matches[1]) ? $matches[1] : 0;

		return $this->steamID;
	}
}