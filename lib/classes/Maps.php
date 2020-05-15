<?php

use system\Core;

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 08.02.2017
 * Time: 18:04
 */
class Maps
{
	/**
	 * @return array $difficulties
	 */
    public static function getAllMaps()
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                maps
            '
        );
        $cmd = Core::getDB()->prepareStatement($query);
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
    public static function getMapsWithCategory($mapCategoryID)
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
        $cmd = Core::getDB()->prepareStatement($query);
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