<?php

class Utility
{
    public static function getGetParameter($removePage = true)
    {
        $getParameter = '';
        foreach ($_GET as $key => $value) {
            if ($removePage && $key == 'page') {

            } else {
                $getParameter = $getParameter . '&' . $key . '=' . $value;
            }

        }
        return $getParameter;
    }

    public static function getSteamAvatarMedium($steamID)
    {
        $url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . STEAMAPIKEY . "&steamids=" . $steamID);
        $content = json_decode($url, true);
        return $content['response']['players'][0]['avatarmedium'];
    }

    public static function getSteamName($steamID)
    {
        $url = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . STEAMAPIKEY . "&steamids=" . $steamID);
        $content = json_decode($url, true);
        return $content['response']['players'][0]['personaname'];
    }

    public static function varDump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }
}