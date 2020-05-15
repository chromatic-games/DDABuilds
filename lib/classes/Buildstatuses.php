<?php

use system\Core;

class Buildstatuses
{
    public static function getAllStatuses()
    {

        $query = sprintf('
            SELECT
                *
            FROM 
                buildstatuses
            ');
        $cmd = Core::getDB()->prepareStatement($query);
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