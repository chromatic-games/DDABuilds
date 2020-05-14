<?php

class BuildStatus extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'buildstatuses';
    }
}