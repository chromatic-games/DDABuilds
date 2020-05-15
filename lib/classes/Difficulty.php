<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 08.02.2017
 * Time: 16:59
 */
class Difficulty extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'difficulties';
    }
}