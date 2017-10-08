<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.10.2017
 * Time: 19:51
 */
class cDB
{
    private $dbLink;

    function __construct()
    {
        $this->dbLink = new mysqli(Constants::$DB_HOST_NAME, Constants::$DB_LOGIN, Constants::$DB_PASSWORD, Constants::$DB_NAME);
        var_dump($this->dbLink);
    }

    public function SaveTeacher($teacher)
    {
        return $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_TEACHERS . "`(`nickName`, `passwordHash`, `curr_session_hash`, `extraInfo`,`groups`) VALUES ('" . $teacher->nickName . "','" . $teacher->passwordHash . "','" . $teacher->getCurrentHash() . "','" . $teacher->extraInfo . "','" . $teacher->getGroupsIDs() . "')");
    }
    public function LoadTeacherByNickName($nickName)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `teachers` WHERE `nickName` = '". $nickName ."'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cTeacher($dbObject->nickName, $dbObject->passwordHash);
        $retRecord->extraInfo = $dbObject->extraInfo;
        foreach (explode(',',$dbObject->groups) as $key => $value)
        {
            $retRecord->groups[$value] = LoadGroupByID($value);

        }
    }
    public function LoadGroupByID($nickName)
    {
        //TODO: realise
    }
}