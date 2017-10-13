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
    }

    public function SaveTeacher($teacher)
    {
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_TEACHERS . "`(`nickName`, `passwordHash`, `curr_session_hash`, `extraInfo`,`groups`) VALUES ('" . $teacher->nickName . "','" . $teacher->passwordHash . "','" . "" . "','" . $teacher->extraInfo . "','" . $teacher->getGroupsIDs() . "')");
        foreach ($teacher->groups as $key => $value)
            $bool1 = $bool1 && $this->SaveGroup($value);
        return $bool1;
    }

    public function SaveGroup($group)
    {
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_GROUPS . "`(`id`, `timetable`, `students`, `extraInfo`) VALUES ('" . $group->groupID . "','" . $group->getTimetable() . "','" . $group->getStudentsIDs() . "','" . $group->getGroupInfo() . "')");
        foreach ($group->students as $key => $value)
            $bool1 = $bool1 && $this->SaveStudent($value);
        return $bool1;
    }

    public function SaveStudent($student)
    {
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_STUDENTS . "`(`nickName`, `passwordHash`, `curr_session_hash`, `extraInfo`) VALUES ('$student->nickName','$student->passwordHash','','$student->extraInfo')");
        //сохранение расписания
        return $bool1;
    }

    public function LoadTeacherByNickName($nickName)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `teachers` WHERE `nickName` = '" . $nickName . "'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cTeacher($dbObject->nickName, $dbObject->passwordHash);
        $retRecord->extraInfo = $dbObject->extraInfo;
        foreach (explode(',', $dbObject->groups) as $key => $value) {
            $retRecord->addGroup($this->LoadGroupByID($value));
        }
        return $retRecord;
    }

    public function LoadGroupByID($groupID)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `groups` WHERE `id` = '" . $groupID . "'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cGroup($dbObject->id, $dbObject->extraInfo);
        //не умеет работать с расписанием
        foreach (explode(',', $dbObject->students) as $key => $value) {
            $retRecord->addStudent($this->LoadStudentByNickName($value));
        }
        return $retRecord;
    }

    public function LoadStudentByNickName($nickName)
    {
        //TODO загрузка записи ученика
        return null;
    }

    public function LogIn($nick, $password, &$outMessage)
    {
        $outMessage = "";
        $dbAnswerTeachers = $this->dbLink->query("SELECT * FROM `" . Constants::$DB_TABLE_TEACHERS . "` WHERE `nickName` = '$nick'");
        $dbAnswerStudents = $this->dbLink->query("SELECT * FROM `" . Constants::$DB_TABLE_STUDENTS . "` WHERE `nickName` = '$nick'");

        if ($dbAnswerTeachers->num_rows == 0 && $dbAnswerStudents->num_rows == 0) {
            $outMessage = Constants::$INCORRECT_LOGIN_MESSAGE;
            return null;
        }

        if ($dbAnswerTeachers->num_rows != 0) {
            $isTeacher = true;
            $userInfo = $dbAnswerTeachers->fetch_array();
        } else {
            $isTeacher = false;
            $userInfo = $dbAnswerStudents->fetch_array();
        }

        if (!password_verify($password, $userInfo['passwordHash'])) {

            $outMessage = Constants::$INCORRECT_PASSWORD_MESSAGE;
            return null;
        }

        $currSessionHash = Constants::random_string(10);
        if ($isTeacher) {

            $retRecord = new cTeacher($userInfo['nickName'], "unavailable");
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_TEACHERS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
        } else {
            $retRecord = new cStudent($userInfo['nickName'], "unavailable");
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_TEACHERS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
        }

        var_dump($_COOKIE);

        setcookie("id", $userInfo['nickName']);
        setcookie("curr_session_hash", $currSessionHash);

        $hash = $_COOKIE['curr_session_hash'];
        echo $currSessionHash;
        var_dump($_COOKIE);

        return $retRecord;
    }

    public function LogOut($currUser)
    {
        $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_TEACHERS . " SET curr_session_hash = '' WHERE nickName ='" . $currUser['nickName'] . "'");
        setcookie("id", "");
        setcookie("currSessionHash", "");
    }

    public function VerifyUser()
    {
        if(!isset($_COOKIE['id']))  return false;

        $nick = $_COOKIE['id'];
        $hash = $_COOKIE['curr_session_hash'];

        $dbAnswerTeachers = $this->dbLink->query("SELECT `curr_session_hash` FROM `" . Constants::$DB_TABLE_TEACHERS . "` WHERE `nickName` = '$nick'");
        $dbAnswerStudents = $this->dbLink->query("SELECT `curr_session_hash` FROM `" . Constants::$DB_TABLE_STUDENTS . "` WHERE `nickName` = '$nick'");

        if ($dbAnswerTeachers->num_rows == 0 && $dbAnswerStudents->num_rows == 0)
            return null;

        $isTeacher = $dbAnswerTeachers->num_rows != 0;

        if ($isTeacher) {
            $dbHash = $dbAnswerTeachers->fetch_row()[0];
        } else {
            $dbHash = $dbAnswerStudents->fetch_row()[0];
        }
        $hash = $_COOKIE['curr_session_hash'];

        return $hash == $dbHash;
    }
}