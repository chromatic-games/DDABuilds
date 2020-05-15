<?php

use system\Core;

class Build extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'builds';
    }

    public function save()
    {
        $response = parent::save();
        $lastInsert = Core::getDB()->getInsertID($this->tablename);
        if ($lastInsert != '0') {
            return $lastInsert;
        } else {
            return $response;
        }
    }

    public function increaseViewCount()
    {
        $count = $this->getData('views') + 1;

        $this->setData('views', $count);
        parent::save();
    }
}