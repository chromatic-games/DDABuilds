<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 08.02.2017
 * Time: 17:58
 */
class Difficulties
{
    /**
     * @param PDO $oDBH
     * @return array $difficulties
     */
    public static function getAllDifficulties($oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                difficulties
            ');
        $cmd = $oDBH->prepare($query);
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