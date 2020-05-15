<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 10.02.2017
 * Time: 17:27
 */
class Vote extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'votes';
    }
}