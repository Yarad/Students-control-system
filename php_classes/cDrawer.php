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
            $tempStr = str_replace("{groupName}", $value->getGroupInfo(), $template);
            $tempStr = str_replace("{groupID}", $value->groupID, $tempStr);
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
            $tempStr = str_replace("{NameSurname}", $value->surname_name, $template);
            //$tempStr = str_replace("{averageMark}", $value->getAverageMark(), $tempStr);
            $tempStr = str_replace("{studentLogin}", $value->nickName, $tempStr);
            $resStr .= $tempStr;
        }
        return $resStr;
    }

    static function DrawTimetableHeader($monthName, $yearNum, $isTeacher)
    {
        if ($isTeacher)
            $str = file_get_contents(Constants::$ROOT_PATH . "html_templates/teacherTimetableHeader.html");
        else
            $str = file_get_contents(Constants::$ROOT_PATH . "html_templates/studentTimetableHeader.html");

        $str = str_replace("{month}", $monthName, $str);
        $str = str_replace("{year}", $yearNum, $str);
        return $str;
    }

    static function DrawStudentTimetableToEdit($student, $weekTimetable, $monthOffset, $isTeacher = true)
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
        $end = new DateTime(date('Y-m-t'));

        if ($monthOffset > 0) {
            $start->add(new DateInterval("P" . $monthOffset . "M"));
            $end->add(new DateInterval("P" . $monthOffset . "M"));
        } else {
            $start->sub(new DateInterval("P" . abs($monthOffset) . "M"));
            $end->sub(new DateInterval("P" . abs($monthOffset) . "M"));
        }
        $dateInterval = new DateInterval("P1D");
        $dateRange = new DatePeriod($start, $dateInterval, $end);

        $week = 1;

        foreach ($dateRange as $dt) {
            foreach ($weekTimetable->getDaysArray() as $value) {
                $dayNum = $value->day;
                $time = explode('.', $value->time);

                if ($dt->format('N') == $dayNum) {
                    $dt->SetTime($time[0], $time[1]);
                    $resArr[$week][] = $dt;
                }
            }
            if ($dt->format('N') == 7)
                $week++;
        }
        //здесь в resArray массив дат, разюитый по неделям

        //var_dump($student->calendarMarks);
        foreach ($resArr as $week) {
            $tempWeekStr = '';
            foreach ($week as $day) {
                $tempOneDay = $oneDayTemplate;
                $tempOneDay = str_replace("{date}", Constants::printRusDate($day), $tempOneDay);
                $tempOneDay = str_replace("{time}", $day->format("H.i"), $tempOneDay);

                if ($isTeacher) {
                    //отрисовка для учителя
                    if (isset($student->calendarMarks[$day->format("d.m.Y")]->note))
                        $tempOneDay = str_replace("{note}", "<textarea class='note-edit' id='" . $day->format("d.m.Y") . "'>" . $student->calendarMarks[$day->format("d.m.Y")]->note . "</textarea>", $tempOneDay);
                    else
                        $tempOneDay = str_replace("{note}", "<textarea class='note-edit' id='" . $day->format("d.m.Y") . "'></textarea>", $tempOneDay);

                    if (isset($student->calendarMarks[$day->format("d.m.Y")]->mark))
                        $tempOneDay = str_replace("{mark}", "<textarea class='mark-edit' id='" . $day->format("d.m.Y") . "'>" . $student->calendarMarks[$day->format("d.m.Y")]->mark . "</textarea>", $tempOneDay);
                    else
                        $tempOneDay = str_replace("{mark}", "<textarea class='mark-edit' id='" . $day->format("d.m.Y") . "'></textarea>", $tempOneDay);
                } else {
                    //отрисовка для студента
                    if (isset($student->calendarMarks[$day->format("d.m.Y")]->note))
                        $tempOneDay = str_replace("{note}", "<div class='note-edit' id='" . $day->format("d.m.Y") . "'>" . $student->calendarMarks[$day->format("d.m.Y")]->note . "</div>", $tempOneDay);
                    else
                        $tempOneDay = str_replace("{note}", "<div class='note-edit' id='" . $day->format("d.m.Y") . "'></div>", $tempOneDay);

                    if (isset($student->calendarMarks[$day->format("d.m.Y")]->mark))
                        $tempOneDay = str_replace("{mark}", "<div class='mark-edit' id='" . $day->format("d.m.Y") . "'>" . $student->calendarMarks[$day->format("d.m.Y")]->mark . "</div>", $tempOneDay);
                    else
                        $tempOneDay = str_replace("{mark}", "<div class='mark-edit' id='" . $day->format("d.m.Y") . "'></div>", $tempOneDay);

                }
                $tempWeekStr .= $tempOneDay;
            }
            $resStr .= str_replace("{days}", $tempWeekStr, $oneWeekTemplate);
        }

        return $resStr;
    }

    static function DrawEmptyTimetableToEdit($weekTimetable, $monthOffset)
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
        $end = new DateTime(date('Y-m-t'));

        if ($monthOffset > 0) {
            $start->add(new DateInterval("P" . $monthOffset . "M"));
            $end->add(new DateInterval("P" . $monthOffset . "M"));
        } else {
            $start->sub(new DateInterval("P" . abs($monthOffset) . "M"));
            $end->sub(new DateInterval("P" . abs($monthOffset) . "M"));
        }
        $dateInterval = new DateInterval("P1D");
        $dateRange = new DatePeriod($start, $dateInterval, $end);

        $week = 1;

        foreach ($dateRange as $dt) {
            foreach ($weekTimetable->getDaysArray() as $value) {
                $dayNum = $value->day;
                $time = explode('.', $value->time);

                if ($dt->format('N') == $dayNum) {
                    $dt->SetTime($time[0], $time[1]);
                    $resArr[$week][] = $dt;
                }
            }
            if ($dt->format('N') == 7)
                $week++;
        }
        //здесь в resArray массив дат, разюитый по неделям

        //var_dump($student->calendarMarks);
        foreach ($resArr as $week) {
            $tempWeekStr = '';
            foreach ($week as $day) {
                $tempOneDay = $oneDayTemplate;
                $tempOneDay = str_replace("{date}", Constants::printRusDate($day), $tempOneDay);
                $tempOneDay = str_replace("{time}", $day->format("H.i"), $tempOneDay);
                $tempOneDay = str_replace("{note}", "<textarea class='note-edit' id='" . $day->format("d.m.Y") . "'></textarea>", $tempOneDay);
                $tempOneDay = str_replace("{mark}", "<textarea class='mark-edit' id='" . $day->format("d.m.Y") . "'></textarea>", $tempOneDay);

                $tempWeekStr .= $tempOneDay;
            }
            $resStr .= str_replace("{days}", $tempWeekStr, $oneWeekTemplate);
        }

        return $resStr;
    }
}