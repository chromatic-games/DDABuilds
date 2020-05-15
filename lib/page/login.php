<?php

use system\steam\LightOpenID;
use system\steam\Steam;
use system\steam\SteamUser;

try {
	$openid = new LightOpenID(BASE_URL);

	if ( !$openid->mode ) {
		$openid->identity = 'https://steamcommunity.com/openid';
		header('Location: '.$openid->authUrl());
	}
	elseif ( $openid->mode == 'cancel' ) {
		echo 'User has canceled authentication!';
	}
	elseif ( $openid->validate() ) {
		$id = $openid->identity;
		$ptn = "/^https?:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
		preg_match($ptn, $id, $matches);

		$_SESSION['steamid'] = $matches[1];
		$_SESSION['steam_profile'] = (new SteamUser($matches[1]))->getData();

		if ( !headers_sent() ) {
			header('Location: /');
			exit;
		}
		else {
			?>
			<script type="text/javascript">
				window.location.href = '<?php echo $steamauth['loginpage']; ?>';
			</script>
			<noscript>
				<meta http-equiv="refresh" content="0;url=<?php echo $steamauth['loginpage']; ?>" />
			</noscript>
			<?php
			exit;
		}
	}
	else {
		echo "User is not logged in.\n";
	}
} catch ( ErrorException $e ) {
	echo $e->getMessage();
}