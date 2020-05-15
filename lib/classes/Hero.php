<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 20.02.2017
 * Time: 01:06
 */
class Hero extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'classes';
    }
}