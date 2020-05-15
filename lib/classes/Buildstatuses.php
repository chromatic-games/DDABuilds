<?php

class Buildstatuses
{
    public static function getAllStatuses($oDBH)
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                buildstatuses
            ');
        $cmd = $oDBH->prepare($query);
        $cmd->execute();
        $buildstatuses = array();
        while ($row = $cmd->fetch()) {
            $buildstatus = new BuildStatus();
            $buildstatus->setID($row['id']);
            if (!$buildstatus->load()) {
                continue;
            }
            $buildstatuses[] = $buildstatus;
        }
        return $buildstatuses;
    }
}