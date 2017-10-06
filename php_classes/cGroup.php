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
    private $students;
    private $groupInfo;
    public $groupTimetable; //не знаю, что это такое. Надо смотреть/придумывать

    public function addStudent($student)
    {
        if(!($student instanceof cStudent) ) return false;
        if (isset($this->students[$student->getNickName()])) return false;

        //дописать

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

    
}