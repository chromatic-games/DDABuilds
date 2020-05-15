<?php

use system\steam\Steam;

class Utility
{
    public static function getGetParameter($removePage = true)
    {
        $getParameter = '';
        foreach ($_GET as $key => $value) {
            if ($removePage && $key == 'pageNo') {

            } else {
                $getParameter = $getParameter . '&' . $key . '=' . $value;
            }

        }
        return $getParameter;
    }

	/**
	 * @param $steamID
	 *
	 * @return mixed
	 * @throws Exception
	 * @deprecated
	 */
    public static function getSteamAvatarMedium($steamID)
    {
        return Steam::getInstance()->getAvatarMedium($steamID);
    }

	/**
	 * @param int $steamID
	 *
	 * @return string
	 * @throws Exception
	 * @deprecated
	 */
    public static function getSteamName($steamID)
    {
        return Steam::getInstance()->getDisplayName($steamID);
    }

    public static function varDump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}