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
        foreach ($groups as $key => $value)
        {
            $tempStr = str_replace("{groupName}",$value->groupID, $template);
            $tempStr = str_replace("{groupStudentsAmount}",count($value->students), $tempStr);
            $resStr .= $tempStr;
        }
        return $resStr;
    }

    static function DrawStudentsList($students)
    {
        $template = file_get_contents(Constants::$ROOT_PATH . "html_templates/studentInList.html");
        $resStr = file_get_contents(Constants::$ROOT_PATH . "html_templates/backToGroupsButton.html");
        foreach ($students as $key => $value)
        {
            //временно без имен-фамилий
            $tempStr = str_replace("{NameSurname}",$value->nickName, $template);
            $tempStr = str_replace("{averageMark}",$value->getAverageMark(), $tempStr);
            $tempStr = str_replace("{studentLogin}",$value->nickName, $tempStr);
            $resStr .= $tempStr;
        }
        return $resStr;
    }
}