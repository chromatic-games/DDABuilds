<?php

use system\Core;

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 14.02.2017
 * Time: 03:49
 */
class MapCategories
{
    public static function getAllMapCategories()
    {
        $query = sprintf('
            SELECT
                *
            FROM 
                mapcategories
            '
        );
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute();
        $mapCategories = array();
        while ($row = $cmd->fetch()) {
            $mapCategory = new MapCategory();
            $mapCategory->setID($row['id']);
            if (!$mapCategory->load()) {
                continue;
            }
            $mapCategories[] = $mapCategory;
        }
        return $mapCategories;
    }
}