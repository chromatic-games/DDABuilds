<?php

namespace system\steam;

class Steam {
	const BUTTON_STYLE_RECTANGLE = '01';

	const BUTTON_STYLE_SQUARE    = '02';

	public static function getLoginButton($style = self::BUTTON_STYLE_SQUARE) {
		return '<a href="'.BASE_URL.'?page=login"><img alt="Login" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_'.$style.'.png"></a>';
	}
}