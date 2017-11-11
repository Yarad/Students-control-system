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
    public $calendarMarks = []; //27.05.2017 => [note,mark]
    public $groupID;

    public function __construct($nick, $password, $groupID, $surnameName)
    {
        parent::__construct($nick, $password, $surnameName);
        $this->groupID = $groupID;
    }

    public function editMark($date, $dayInfo)
    {
        $this->calendarMarks[$date] = $dayInfo;
    }

    public function Save()
    {
        $str = json_encode($this);
        file_put_contents(Constants::$ROOT_PATH . "students/" . $this->nickName, $str);
    }

    public static function JsonDecode($jsonStr)
    {
        $arr = json_decode($jsonStr);
        $retRecord = new cStudent($arr["nickName"], $arr["passwordHash"]);
        $retRecord->extraInfo = $arr["extraInfo"];
        return $retRecord;
    }

    public function LoadMarksFromJsonStr($jsonStr)
    {
        $tempObj = json_decode($jsonStr);
        if ($tempObj == null) return;

        foreach ($tempObj as $key => $obj) {
            $this->calendarMarks[$key] = $obj;
        }
        //return json_decode($jsonStr);
    }

    public function GetMarksInJson()
    {
        return json_encode($this->calendarMarks, JSON_UNESCAPED_UNICODE);
    }

    public function getAverageMark()
    {
        return 10;
    }
}