<?php

class Database
{
    /**
     * @var PDO
     */
    private static $oDBH;

    /**
     * @return PDO
     */
    public static function getInstance()
    {
        if (!self::$oDBH) {
            self::_createInstance();
        }
        return self::$oDBH;
    }


    private static function _createInstance()
    {
        if (!class_exists('PDO')) {
            throw new Exception('No PDO installed!');
        }
        if (LIVE) {
            self::$oDBH = new PDO(DBSTRINGLIVE, DBUSERLIVE, DBPASSWORDLIVE);
        } else {
            self::$oDBH = new PDO(DBSTRINGLOCAL, DBUSERLOCAL, DBPASSWORDLOCAL);
        }
    }
}
