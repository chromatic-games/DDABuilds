<?php

class Placeds
{
    /**
     * @param int $iBuild
     * @param PDO $oDBH
     * @return array $towers
     */
    public static function getAllPlacedsForBuild($iBuild, $oDBH)
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
        $cmd = $oDBH->prepare($query);
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
     * @param PDO $oDBH
     * @return array $towers
     */
    public static function getPlacedsForBuild($iBuild, $buildwave, $oDBH)
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
        $cmd = $oDBH->prepare($query);
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

    public static function deletePlacedsForBuild($buildID, $oDBH)
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
        $cmd = $oDBH->prepare($query);
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