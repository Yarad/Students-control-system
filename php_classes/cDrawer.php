<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 14.10.2017
 * Time: 23:27
 */
class cDrawer
{
    static function DrawGroupsList($groups)
    {
        $template = file_get_contents(Constants::$ROOT_PATH . "html_templates/groupInList.html");
        $resStr = "";
        foreach ($groups as $key => $value) {
            $tempStr = str_replace("{groupName}", $value->groupID, $template);
            $tempStr = str_replace("{groupStudentsAmount}", count($value->students), $tempStr);
            $resStr .= $tempStr;
        }
        return $resStr;
    }

    static function DrawStudentsList($students)
    {
        $template = file_get_contents(Constants::$ROOT_PATH . "html_templates/studentInList.html");
        $resStr = "";
        foreach ($students as $key => $value) {
            //временно без имен-фамилий
            $tempStr = str_replace("{NameSurname}", $value->nickName, $template);
            $tempStr = str_replace("{averageMark}", $value->getAverageMark(), $tempStr);
            $tempStr = str_replace("{studentLogin}", $value->nickName, $tempStr);
            $resStr .= $tempStr;
        }
        return $resStr;
    }

    static function DrawStudentTimetableToEdit($student, $weekTimetable, $monthOffset)
    {
        //нарисовать расписание
        //брать буду за текущий месяц
        //студент; monday,15.20; 07

        $oneWeekTemplate = file_get_contents(Constants::$ROOT_PATH . "html_templates/oneCalendarWeek.html");
        $oneDayTemplate = file_get_contents(Constants::$ROOT_PATH . "html_templates/oneCalendarDay.html");

        $resStr = "";
        $resArr = [];
        //получение всех дат в resArr

        $start = new DateTime("Y-m-01", strtotime("+$monthOffset month")); // первый день месяца
        $end = new DateTime('Y-m-t', strtotime("+$monthOffset month")); // последний день месяца ПОД ВОПРОСОМ ЗНАК +

        $dateInterval = new DateInterval("1 day");
        $dateRange = new DatePeriod($start, $dateInterval, $end);

        foreach ($dateRange as $dt) {
            foreach ($weekTimetable as $value) {
                $dayNum = $value['dayOfWeek'];
                $time = $value['time'];

                if ($dt->format('N') == $dayNum)
                    $resArr[] = array("day" => $dt, "time" => $time);
            }
        }

        foreach ($resArr as $value) {
            $resStr .= $value["day"] . $value["time"] . "<br>";
        }

        return $resStr;
    }
}