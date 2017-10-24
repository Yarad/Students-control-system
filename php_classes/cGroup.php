<?php
include_once "cStudent.php";
include_once "cTimetable.php";

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:10
 */
class cGroup
{
    public $students = [];
    private $groupInfo;
    public $groupID;
    public $weekTimetable;
    public $teacherNickName;

    public function __construct($id, $teacherNickName, $groupInfo = "")
    {
        $this->groupID = $id;
        $this->groupInfo = $groupInfo;
        $this->teacherNickName =$teacherNickName;
        $this->weekTimetable = new cTimetable();
    }

    public function addStudent($student)
    {
        if (!($student instanceof cStudent)) return false;
        if (isset($this->students[$student->nickName])) return false;

        $this->students[$student->nickName] = $student;

        return true;
    }

    public function getAverageMark()
    {
        //реализвать позже для прикола
    }

    public function getGroupInfo()
    {
        return $this->groupInfo;
    }

    public function changeGroupInfo($newGroupInfo)
    {
        $this->groupInfo = $newGroupInfo;
    }

    public function getStudentsIDs()
    {
        return implode(',', array_keys($this->students));
    }

    /*буду использовать внутренние метожды weeekTimetable
     * public function addLessonPerWeek($day,$time)
    {
        $this->weekTimetable->addLessonPerWeek($day,$time);
    }*/
}