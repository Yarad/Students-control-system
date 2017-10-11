<?php
include_once "cStudent.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:10
 */
class cGroup
{
    public $students =[];
    private $groupInfo;
    public $groupID;
    public $groupTimetable; //не знаю, что это такое. Надо смотреть/придумывать

    public function __construct($id, $groupInfo="")
    {
        $this->groupID = $id;
        $this->groupInfo = $groupInfo;
    }

    public function addStudent($student)
    {
        if(!($student instanceof cStudent) ) return false;
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

    public function getTimetable()
    {
        return "No timetable";
    }

    public function getStudentsIDs()
    {
        return implode(',',array_keys($this->students));
    }
    
}