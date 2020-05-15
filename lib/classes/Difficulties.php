<?php

use system\Core;

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 08.02.2017
 * Time: 17:58
 */
class Difficulties
{
	/**
	 * @return array $difficulties
	 */
    public static function getAllDifficulties()
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                difficulties
            ');
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute();
        $difficulties = array();
        while ($row = $cmd->fetch()) {
            $difficulty = new Difficulty();
            $difficulty->setID($row['id']);
            if (!$difficulty->load()) {
                continue;
            }
            $difficulties[] = $difficulty;
        }
        return $difficulties;
    }
}