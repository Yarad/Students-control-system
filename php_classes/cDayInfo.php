<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:00
 */
class cDayInfo
{
    public $note;
    public $mark;

    function __construct($note, $mark)
    {
        $this->note = $note;
        $this->mark = $mark;
    }
}