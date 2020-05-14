<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 30.04.2017
 * Time: 17:11
 */
class BuildWaves
{

    public static function getBuildwavesForBuild($buildID, $oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                buildwaves
            WHERE
                fk_build = ?
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($buildID));
        $buildwaves = array();
        while ($row = $cmd->fetch()) {
            $buildwave = new BuildWave();
            $buildwave->setID($row['id']);
            if (!$buildwave->load()) {
                continue;
            }
            $buildwaves[] = $buildwave;
        }
        return $buildwaves;
    }

    public static function deleteBuildwavesForBuild($buildID, $oDBH)
    {
        $query = sprintf('
            SELECT
                id
            FROM 
                buildwaves
            WHERE
                fk_build = ?
            '
        );
        $cmd = $oDBH->prepare($query);
        $cmd->execute(array($buildID));
        while ($row = $cmd->fetch()) {

            $buildwave = new BuildWave();
            $buildwave->setID($row['id']);
            $buildwave->load();
            if (!$buildwave->load()) {
                continue;
            }
            $buildwave->delete();
        }
    }

}