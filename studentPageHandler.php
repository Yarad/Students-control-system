<?php
include_once "php_classes/cDB.php";
include_once "php_classes/cDrawer.php";
include_once "php_classes/сOneDayRecord.php";

$db = new cDB();
$currUser = $db->VerifyUser();

if ($_POST['task'] == "ShowTimetable") {
    $monthOffset = $_POST['monthOffset'];
    $weekTimetable = $db->LoadGroupByID($currUser->groupID,false)->weekTimetable;
    echo cDrawer::DrawTimetableHeader(Constants::getMonthNameByOffset($monthOffset), Constants::getYeraNumByOffset($monthOffset),false) . cDrawer::DrawStudentTimetableToEdit($currUser,$weekTimetable,$monthOffset,false);
}
