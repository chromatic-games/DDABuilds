<?php

use system\Core;

class Placeds
{
    /**
     * @param int $iBuild
     * @return array $towers
     */
    public static function getAllPlacedsForBuild($iBuild)
    {

        $query = sprintf('
            SELECT
                id
            FROM 
                placed
            WHERE
                fk_build = ?
            '
        );
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($iBuild));
        $placeds = array();
        while ($row = $cmd->fetch()) {
            $placed = new Placed();
            $placed->setID($row['id']);
            if (!$placed->load()) {
                continue;
            }
            $placeds[] = $placed;
        }
        return $placeds;
    }

    /**
     * @param int $iBuild
     * @return array $towers
     */
    public static function getPlacedsForBuild($iBuild, $buildwave)
    {

        $query = sprintf('
            SELECT
                id
            FROM 
                placed
            WHERE
                fk_build = ? AND fk_buildwave = ?
            '
        );
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($iBuild, $buildwave));
        $placeds = array();
        while ($row = $cmd->fetch()) {
            $placed = new Placed();
            $placed->setID($row['id']);
            if (!$placed->load()) {
                continue;
            }
            $placeds[] = $placed;
        }
        return $placeds;
    }

    public static function deletePlacedsForBuild($buildID)
    {
        $query = sprintf('
            SELECT
                id
            FROM 
                placed
            WHERE
                fk_build = ?
            '
        );
        $cmd = Core::getDB()->prepareStatement($query);
        $cmd->execute(array($buildID));
        while ($row = $cmd->fetch()) {

            $placed = new Placed();
            $placed->setID($row['id']);
            $placed->load();
            if (!$placed->load()) {
                continue;
            }
            $placed->delete();
        }
    }
}