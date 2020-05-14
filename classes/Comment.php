<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 21.04.2017
 * Time: 14:17
 */
class Comment extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'comments';
    }

    public function save()
    {
        parent::save();
        $oDBH = Database::getInstance();
        return $oDBH->lastInsertId();
    }
}