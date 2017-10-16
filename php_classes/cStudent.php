<?php
include_once "cUser.php";
include_once "Constants.php";

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

    public function __construct($nick, $password)
    {
        parent::__construct($nick, $password);
        //$this->groupID = $groupID;
    }

    public function editMark($date, $dayInfo)
    {
        $this->calendarMarks[$date] = $dayInfo;
    }

    public function Save()
    {
        $str = json_encode($this);
        file_put_contents($ROOT_PATH . "students/" . $this->nickName, $str);
    }

    public static function JsonDecode($jsonStr)
    {
        $arr = json_decode($jsonStr);
        $retRecord = new cStudent($arr["nickName"], $arr["passwordHash"]);
        $retRecord->extraInfo = $arr["extraInfo"];
        return $retRecord;
    }

    public function getAverageMark()
    {
        return 10;
    }
}