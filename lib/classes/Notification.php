<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 27.04.2017
 * Time: 04:40
 */
class Notification extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'notifications';
    }
}