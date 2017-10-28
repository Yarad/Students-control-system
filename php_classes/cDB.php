<?php
include_once "cTeacher.php";
include_once "cUser.php";

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
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_TEACHERS . "`(`nickName`, `passwordHash`, `curr_session_hash`, `extraInfo`,`groups`,`surname_name`) VALUES ('" . $teacher->nickName . "','" . $teacher->passwordHash . "','" . "" . "','" . $teacher->extraInfo . "','" . $teacher->getGroupsIDs() . "','" . $teacher->surname_name . "')");
        foreach ($teacher->groups as $key => $value)
            $bool1 = $bool1 && $this->SaveGroup($value);
        return $bool1;
    }

    public function SaveGroup($group)
    {
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_GROUPS . "`(`id`, `timetable`, `students`, `extraInfo`,`teacherNick`) VALUES ('" . $group->groupID . "','" . $group->weekTimetable->getTimetableInJSON() . "','" . $group->getStudentsIDs() . "','" . $group->getGroupInfo() . "','" . $group->teacherNickName . "')");
        foreach ($group->students as $key => $value)
            $bool1 = $bool1 && $this->SaveStudent($value);
        return $bool1;
    }

    public function SaveStudent($student)
    {
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_STUDENTS . "`(`nickName`, `passwordHash`, `curr_session_hash`, `extraInfo`,`surname_name`,`calendar_of_marks`,`groupID`) VALUES ('$student->nickName','$student->passwordHash','','$student->extraInfo','" . $student->surname_name . "','" . $student->GetMarksInJson() . "','" . $student->groupID . "')");
        return $bool1;
    }

    public function UpdateStudent($student)
    {
        $bool1 = $this->dbLink->query("UPDATE `students` SET `groupID`='" . $student->groupID . "', `extraInfo`='" . $student->extraInfo . "',`surname_name`='" . $student->surname_name . "',`calendar_of_marks`='" . $student->GetMarksInJson() . "' WHERE `nickName`='" . $student->nickName . "'");
        return $bool1;
    }

    public function LoadTeacherByNickName($nickName, $subLayers = true)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `teachers` WHERE `nickName` = '" . $nickName . "'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cTeacher($dbObject->nickName, $dbObject->passwordHash, $dbObject->surname_name);
        $retRecord->extraInfo = $dbObject->extraInfo;
        $retRecord->surname_name = $dbObject->surname_name;

        if ($subLayers)
            foreach (explode(',', $dbObject->groups) as $key => $value) {
                $retRecord->addGroup($this->LoadGroupByID($value));
            }
        return $retRecord;
    }

    public function LoadGroupByID($groupID, $subLayers = true)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `groups` WHERE `id` = '" . $groupID . "'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cGroup($dbObject->id, $dbObject->extraInfo);
        $retRecord->weekTimetable->loadTimetableFromJSON($dbObject->timetable);
        $retRecord->teacherNickName = $dbObject->teacherNick;
        //умеет работать с расписанием
        if ($subLayers)
            foreach (explode(',', $dbObject->students) as $key => $value) {
                $retRecord->addStudent($this->LoadStudentByNickName($value));
            }
        return $retRecord;
    }

    public function LoadStudentByNickName($nickName)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `students` WHERE `nickName` = '" . $nickName . "'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cStudent($dbObject->nickName, $dbObject->passwordHash, $dbObject->groupID, $dbObject->surname_name);
        $retRecord->LoadMarksFromJsonStr($dbObject->calendar_of_marks);
        $retRecord->extraInfo = $dbObject->extraInfo;
        $retRecord->surname_name = $dbObject->surname_name;
        return $retRecord;
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

            $retRecord = new cTeacher($userInfo['nickName'], "unavailable", $userInfo['surname_name']);
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_TEACHERS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
        } else {
            $retRecord = $this->LoadStudentByNickName($userInfo['nickName']);
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_STUDENTS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
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
        $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_TEACHERS . " SET curr_session_hash = '' WHERE nickName ='" . $currUser->nickName . "'");
        setcookie("id", "");
        setcookie("currSessionHash", "");
    }

    public function VerifyUser()
    {
        if (!isset($_COOKIE['id'])) return null;

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

        if ($hash == $dbHash) {
            if ($isTeacher)
                return $this->LoadTeacherByNickName($nick);
            else
                return $this->LoadStudentByNickName($nick);
        } else return null;
    }

}