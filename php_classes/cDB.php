<?php
include_once "cTeacher.php";
include_once "cUser.php";
include_once "cAdmin.php";

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
        if ($this->dbLink != null) {
            $this->dbLink->query("SET NAMES utf8");
            $this->dbLink->query("SET CHARACTER SET utf8");
            $this->dbLink->query("SET character_set_client = utf8");
            $this->dbLink->query("SET character_set_connection = utf8");
            $this->dbLink->query("SET character_set_results = utf8");/**/
        }
    }

    public function SaveAdmin($admin)
    {
        $bool1 = $this->dbLink->query("INSERT INTO `" . Constants::$DB_TABLE_ADMINS . "`(`nickName`, `passwordHash`, `curr_session_hash`) VALUES ('" . $admin->nickName . "','" . $admin->passwordHash . "','')");
        return $bool1;
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

    //удалаяет и из группы
    public function DeleteStudent($student)
    {
        $group = $this->LoadGroupByID($student->groupID);
        $group->DeleteStudent($student->nickName);
        $this->UpdateGroup($group);

        $bool1 = $this->dbLink->query("DELETE FROM `students` WHERE `nickName`='" . $student->nickName . "'");
        return $bool1;
    }

    public function UpdateAdmin($admin)
    {
        $bool1 = $this->dbLink->query("UPDATE `admins` SET `passwordHash`='" . $admin->passwordHash . "' WHERE `nickName`='" . $admin->nickName . "'");
        return $bool1;
    }

    public function UpdateStudent($student)
    {
        $bool1 = $this->dbLink->query("UPDATE `students` SET `passwordHash`='" . $student->passwordHash . "', `groupID`='" . $student->groupID . "', `extraInfo`='" . $student->extraInfo . "',`surname_name`='" . $student->surname_name . "',`calendar_of_marks`='" . $student->GetMarksInJson() . "' WHERE `nickName`='" . $student->nickName . "'");
        return $bool1;
    }

    public function UpdateTeacher($teacher)
    {
        $bool1 = $this->dbLink->query("UPDATE `teachers` SET `passwordHash`='" . $teacher->passwordHash . "',`groups`='" . $teacher->getGroupsIDs() . "',`extraInfo`='" . $teacher->extraInfo . "',`surname_name`='" . $teacher->surname_name . "' WHERE `nickName`='" . $teacher->nickName . "'");
        return $bool1;
    }

    public function UpdateGroup($group)
    {
        $bool1 = $this->dbLink->query("UPDATE `groups` SET `timetable`='" . $group->weekTimetable->getTimetableInJSON() . "',`students`='" . $group->getStudentsIDs() . "',`extraInfo`='" . $group->getGroupInfo() . "',`teacherNick`='" . $group->teacherNickName . "' WHERE `id`='" . $group->groupID . "'");
        return $bool1;
    }

    public function LoadAdminByNickName($nickName)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `admins` WHERE `nickName` = '" . $nickName . "'");
        $dbObject = $dbAnswer->fetch_object();
        if ($dbObject != null) {
            $retRecord = new cAdmin($dbObject->nickName, "", "");
            $retRecord->passwordHash = $dbObject->passwordHash;

            //if ($subLayers)
            //    foreach (explode(',', $dbObject->teachers) as $key => $value) {
            //        $retRecord->addGroup($this->LoadGroupByID($value));
        } else
            $retRecord = null;
        return $retRecord;
    }

    public
    function LoadAllTeachers()
    {
        $retArray = [];
        $dbAnswer = $this->dbLink->query("SELECT `nickName` FROM `teachers` WHERE 1");
        if($dbAnswer == null) return $retArray;
        $count = $dbAnswer->num_rows;
        $dbArray = [];
        for($i=0;$i<$count;$i++)
            $dbArray[] = $dbAnswer->fetch_object()->nickName;

        foreach ($dbArray as $currTeacherNick)
            $retArray[$currTeacherNick] = $this->LoadTeacherByNickName($currTeacherNick, false);
        //Constants::Log(var_export($retArray, true));
        return $retArray;
    }

    public
    function LoadTeacherByNickName($nickName, $subLayers = true)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `teachers` WHERE `nickName` = '" . $nickName . "'");
        $dbObject = $dbAnswer->fetch_object();
        $retRecord = new cTeacher($dbObject->nickName, "", $dbObject->surname_name);
        $retRecord->passwordHash = $dbObject->passwordHash;
        $retRecord->extraInfo = $dbObject->extraInfo;
        $retRecord->surname_name = $dbObject->surname_name;

        if ($subLayers)
            foreach (explode(',', $dbObject->groups) as $key => $value) {
                $retRecord->addGroup($this->LoadGroupByID($value));
            }
        return $retRecord;
    }

    public
    function LoadGroupByID($groupID, $subLayers = true)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `groups` WHERE `id` = '" . $groupID . "'");
        $dbObject = $dbAnswer->fetch_object();
        //var_dump($dbObject);
        $retRecord = new cGroup($dbObject->id, $dbObject->teacherNick, $dbObject->extraInfo);
        $retRecord->weekTimetable->loadTimetableFromJSON($dbObject->timetable);
        $retRecord->teacherNickName = $dbObject->teacherNick;
        //умеет работать с расписанием
        if ($subLayers)
            foreach (explode(',', $dbObject->students) as $key => $value) {
                $retRecord->addStudent($this->LoadStudentByNickName($value));
            }
        return $retRecord;
    }

    public
    function LoadStudentByNickName($nickName)
    {
        $dbAnswer = $this->dbLink->query("SELECT * FROM `students` WHERE `nickName` = '" . $nickName . "'");
        $dbObject = $dbAnswer->fetch_object();

        if ($dbObject == null)
            return null;
        $retRecord = new cStudent($dbObject->nickName, "", $dbObject->groupID, $dbObject->surname_name);
        $retRecord->LoadMarksFromJsonStr($dbObject->calendar_of_marks);
        $retRecord->passwordHash = $dbObject->passwordHash;
        $retRecord->extraInfo = $dbObject->extraInfo;
        $retRecord->surname_name = $dbObject->surname_name;
        return $retRecord;
    }

    public
    function LogIn($nick, $password, &$outMessage)
    {
        $outMessage = "";
        $retRecord = null;

        $dbAnswerAdmins = $this->dbLink->query("SELECT * FROM `" . Constants::$DB_TABLE_ADMINS . "` WHERE `nickName` = '$nick'");
        $dbAnswerTeachers = $this->dbLink->query("SELECT * FROM `" . Constants::$DB_TABLE_TEACHERS . "` WHERE `nickName` = '$nick'");
        $dbAnswerStudents = $this->dbLink->query("SELECT * FROM `" . Constants::$DB_TABLE_STUDENTS . "` WHERE `nickName` = '$nick'");

        if ($dbAnswerTeachers->num_rows == 0 && $dbAnswerStudents->num_rows == 0 && $dbAnswerAdmins->num_rows == 0) {
            $outMessage = Constants::$INCORRECT_LOGIN_MESSAGE;
            return null;
        }

        $isAdmin = false;
        $isTeacher = false;
        $isStudent = false;

        if ($dbAnswerTeachers->num_rows != 0) {
            $isTeacher = true;
            $userInfo = $dbAnswerTeachers->fetch_array();
        } elseif ($dbAnswerStudents->num_rows != 0) {
            $isStudent = true;
            $userInfo = $dbAnswerStudents->fetch_array();
        } else {
            $isAdmin = true;
            $userInfo = $dbAnswerAdmins->fetch_array();
        }

        if (!password_verify($password, $userInfo['passwordHash'])) {
            $outMessage = Constants::$INCORRECT_PASSWORD_MESSAGE;
            return null;
        }

        $currSessionHash = Constants::random_string(10);
        if ($isTeacher) {
            $retRecord = $this->LoadTeacherByNickName($userInfo['nickName']);
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_TEACHERS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
        }

        if ($isStudent) {
            $retRecord = $this->LoadStudentByNickName($userInfo['nickName']);
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_STUDENTS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
        }

        if ($isAdmin) {
            $retRecord = $this->LoadAdminByNickName($userInfo['nickName']);
            $retRecord->currSessionHash = $currSessionHash;
            $this->dbLink->query("UPDATE " . Constants::$DB_TABLE_ADMINS . " SET curr_session_hash = '$currSessionHash' WHERE nickName ='" . $userInfo['nickName'] . "'");
        }

        setcookie("id", $userInfo['nickName']);
        setcookie("curr_session_hash", $currSessionHash);

        $hash = $_COOKIE['curr_session_hash'];
        echo $currSessionHash;
        //var_dump($_COOKIE);

        return $retRecord;
    }

    public function LogOut($currUser)
    {
        $dbNameStr = "";
        if ($currUser instanceof cStudent)
            $dbNameStr = Constants::$DB_TABLE_STUDENTS;

        if ($currUser instanceof cTeacher)
            $dbNameStr = Constants::$DB_TABLE_TEACHERS;

        if ($currUser instanceof cAdmin)
            $dbNameStr = Constants::$DB_TABLE_ADMINS;

        $this->dbLink->query("UPDATE " . $dbNameStr . " SET curr_session_hash = '' WHERE nickName ='" . $currUser->nickName . "'");
        setcookie("id", "");
        setcookie("currSessionHash", "");
    }

    public function VerifyUser()
    {
        if (!isset($_COOKIE['id'])) return null;

        $nick = $_COOKIE['id'];
        $hash = $_COOKIE['curr_session_hash'];
        $dbHash = '';

		//var_dump(isset(Constants::$DB_TABLE_ADMINS));
        $dbAnswerTeachers = $this->dbLink->query("SELECT `curr_session_hash` FROM `" . Constants::$DB_TABLE_TEACHERS . "` WHERE `nickName` = '$nick'");
		$dbAnswerStudents = $this->dbLink->query("SELECT `curr_session_hash` FROM `" . Constants::$DB_TABLE_STUDENTS . "` WHERE `nickName` = '$nick'");
		$dbAnswerAdmins = $this->dbLink->query("SELECT `curr_session_hash` FROM `" . Constants::$DB_TABLE_ADMINS . "` WHERE `nickName` = '$nick'");
		
        if ($dbAnswerTeachers->num_rows == 0 && $dbAnswerStudents->num_rows == 0 && $dbAnswerAdmins->num_rows == 0)
            return null;

        $isTeacher = $dbAnswerTeachers->num_rows != 0;
        $isStudents = $dbAnswerStudents->num_rows != 0;
        $isAdmins = $dbAnswerAdmins->num_rows != 0;

        if ($isTeacher) {
            $dbHash = $dbAnswerTeachers->fetch_row()[0];
        }

        if ($isStudents) {
            $dbHash = $dbAnswerStudents->fetch_row()[0];
        }

        if ($isAdmins) {
            $dbHash = $dbAnswerAdmins->fetch_row()[0];
        }
        //var_dump($isAdmins);
        if ($hash == $dbHash) {
            if ($isTeacher)
                return $this->LoadTeacherByNickName($nick);
            if ($isStudents)
                return $this->LoadStudentByNickName($nick);
            if ($isAdmins)
                return $this->LoadAdminByNickName($nick);
        } else
            return null;
    }
}