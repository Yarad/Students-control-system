<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.10.2017
 * Time: 12:21
 */
class сOneDayRecord
{
    public $mark;
    public $note;

    public function __construct($note, $mark)
    {
        $this->mark = $mark;
        $this->note = $note;
    }
}