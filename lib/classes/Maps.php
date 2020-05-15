<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 08.02.2017
 * Time: 18:04
 */
class Maps
{
    /**
     * @param PDO $oDBH
     * @return array $difficulties
     */
    public static function getAllMaps($oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                maps
            '
        );
        $cmd = $oDBH->prepare($query);
        $cmd->execute();
        $maps = array();
        while ($row = $cmd->fetch()) {
            $map = new Map();
            $map->setID($row['id']);
            if (!$map->load()) {
                continue;
            }
            $maps[] = $map;
        }
        return $maps;
    }
    //TODO: NEEDS FUNCTIONALITY
    public static function getMapsWithCategory($mapCategoryID, $oDBH)
    {
        $query = sprintf('
            SELECT
                *
            FROM 
                maps
            WHERE
                fk_mapcategory = ?
            ORDER BY
                id
            ASC
            '
        );
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($mapCategoryID));
        $maps = array();
        while ($row = $cmd->fetch()) {
            $map = new Map();
            $map->setID($row['id']);
            if (!$map->load()) {
                continue;
            }
            $maps[] = $map;
        }
        return $maps;
    }
}