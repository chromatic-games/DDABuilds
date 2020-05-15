<?php

class Build extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'builds';
    }

    public function save()
    {
        $response = parent::save();
        $oDBH = Database::getInstance();
        $lastInsert = $oDBH->lastInsertId();
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