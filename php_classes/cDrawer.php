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

        $start = new DateTime(date('Y-m-01')); // первый день месяца
        $start->add(new DateInterval("P" . $monthOffset . "M"));

        $end = new DateTime(date('Y-m-t')); // последний день месяца ПОД ВОПРОСОМ ЗНАК +
        $end->add(new DateInterval("P" . $monthOffset . "M"));

        $dateInterval = new DateInterval("P1D");
        $dateRange = new DatePeriod($start, $dateInterval, $end);

        $week=1;
        foreach ($dateRange as $dt) {
            foreach ($weekTimetable->getDaysArray() as $value) {
                $dayNum = $value->day;
                $time = $value->time;

                if ($dt->format('N') == $dayNum)
                    $resArr[$week][] = array("day" => $dt, "time" => $time);
            }
            $week++;
        }


        foreach ($resArr as $week) {
            $tempStr= $oneWeekTemplate;
            $temp2Str =$oneDayTemplate;
            foreach ($week as $day) {
                $temp2Str = str_replace();
            }
        }

        return $resStr;
    }
}