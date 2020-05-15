<?php

use system\Core;

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 20.02.2017
 * Time: 01:06
 */
class Heroes
{
    public static function getAllHeroes()
    {
        $query = sprintf('
            SELECT
                *
            FROM 
                classes
            '
        );
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute();
        $heroes = array();
        while ($row = $cmd->fetch()) {
            $hero = new Hero();
            $hero->setID($row['id']);
            if (!$hero->load()) {
                continue;
            }
            $heroes[] = $hero;
        }
        return $heroes;
    }
}