<?php

/**
 * Created by PhpStorm.
 * User: Chakratos
 * Date: 21.04.2017
 * Time: 18:02
 */
class CommentVote extends DataObject
{
    public function __construct()
    {
        $this->tablename = 'commentvotes';
    }
}