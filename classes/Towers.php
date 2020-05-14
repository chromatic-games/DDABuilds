<?php

class Towers
{
    /**
     * @param int $iClass 1 Squire 2 Apprentice 3 Huntress 4 Monk 5 Series-EV 6 Summoner 7 Jester 20 World 21 Hints 22 Arrows
     * @param PDO $oDBH
     * @return array $towers
     */
    public static function getTowersForClass($iClass, $oDBH)
    {

        $query = sprintf('
            SELECT
                id
            FROM 
                towers
            WHERE
                fk_class = ?
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($iClass));
        $towers = array();
        while ($row = $cmd->fetch()) {
            $tower = new Tower();
            $tower->setID($row['id']);
            if (!$tower->load()) {
                continue;
            }
            $towers[] = $tower;
        }
        return $towers;
    }

    /**
     * @param string $beam proton, reflection, shock, towerbuff
     * @param $oDBH
     * @return array
     */
    public static function getEVBeams($beam, $oDBH)
    {
        $first = 0;
        $second = 0;
        if ($beam == 'proton') {
            $first = 54;
            $second = 57;
        } else if ($beam == 'physical') {
            $first = 58;
            $second = 61;
        } else if ($beam == 'reflection') {
            $first = 62;
            $second = 64;
        } else if ($beam == 'shock') {
            $first = 65;
            $second = 68;
        } else if ($beam == 'towerbuff') {
            $first = 69;
            $second = 71;
        }
        $query = sprintf('
            SELECT
                id
            FROM 
                towers
            WHERE
                id >= ? AND id <= ?
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($first ,$second));
        $towers = array();
        while ($row = $cmd->fetch()) {
            $tower = new Tower();
            $tower->setID($row['id']);
            if (!$tower->load()) {
                continue;
            }
            $towers[] = $tower;
        }
        return $towers;
    }

}