<?php

class Tower extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'towers';
    }

    public function getImage()
    {
        return '/images/tower/' . strtolower(str_replace(' ', '_', $this->getData('name'))) . '.png';
    }
}