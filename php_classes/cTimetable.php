<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.10.2017
 * Time: 17:29
 */
class cTimetable
{
    private $arrayOfDays = [];

    public function getTimetableInJSON()
    {
        return json_encode($this->arrayOfDays);
    }

    public function loadTimetableFromJSON($inputStr)
    {
        $this->arrayOfDays = json_decode($inputStr);
    }

    public function addLessonPerWeek($day, $time)
    {
        $this->arrayOfDays[] = array("day" => $day, "time" => $time);
    }

    public function getDaysArray()
    {
        return $this->arrayOfDays;
    }
}