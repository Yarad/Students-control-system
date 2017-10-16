<?php
include_once "cGroup.php";
include_once "cUser.php";
include_once "Constants.php";

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:07
 */
class cTeacher extends cUser
{
    public $groups;

    public function addGroup($group)
    {
        if (isset($this->groups[$group->groupID]))
            return false;
        else {
            $this->groups[$group->groupID] = $group;
            return false;
        }
    }

    public function Save()
    {
        $str = json_encode($this);
        echo Constants::$ROOT_PATH . "teachers/";
        file_put_contents(Constants::$ROOT_PATH . "teachers/" . $this->nickName . ".json", $str);
    }

    public static function JsonDecode($jsonStr)
    {
        $arr = json_decode($jsonStr);
        $retRecord = new cTeacher($arr["nickName"],$arr["passwordHash"]);
        $retRecord->extraInfo = $arr["extraInfo"];
        return $retRecord;
    }

    public function getGroupsIDs()
    {
        return implode(',',array_keys($this->groups));
    }
}