<?php
include_once "cUser.php";

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 19:50
 */
class cStudent extends cUser
{
    private $calendarMarks; //key: date; value: mark
    public $groupID;

    public function __construct($nick, $password, $groupID)
    {
        parent::__construct($nick, $password);
        $this->groupID = $groupID;
    }

    public function editMark($date, $dayInfo)
    {
        $this->calendarMarks[$date] = $dayInfo;
    }
}