<?php
//каждый раз придется подсоединяться
include_once "php_classes/cDB.php";
include_once "php_classes/Constants.php";
include_once "php_classes/cDrawer.php";

$db = new cDB();
$currUser = $db->VerifyUser();

if ($currUser == null)
    Constants::authFailed();

$page = file_get_contents("html_templates/userMainPage.html");

$scriptToAdd = '';
$cssToAdd = '';
$leftContent = '';

$scriptToAdd .= '<script type="application/javascript" src="js/commonPageHandlers.js"></script>';

if ($currUser instanceof cTeacher) {
    $scriptToAdd .= '<script type="application/javascript" src="js/teacherPageHandlers.js"></script>';
    $cssToAdd .= '<link rel="stylesheet" href="css/teacher.css">';
}

if ($currUser instanceof cStudent) {
    $cssToAdd .= '<link rel="stylesheet" href="css/student.css">';
    $leftContent = cDrawer::DrawTimetableHeader(Constants::getMonthNameByOffset(0), Constants::getYeraNumByOffset(0),false) . cDrawer::DrawStudentTimetableToEdit($currUser, $db->LoadGroupByID($currUser->groupID, false)->weekTimetable, 0, false);
    $scriptToAdd .= "<script> currGroupID = '" . $currUser->groupID . "';  currStudentID = '" . $currUser->nickName . "';</script>";
    $scriptToAdd .= '<script type="application/javascript" src="js/studentPageHandlers.js"></script>';
}

$page = str_replace('{scriptToAdd}', $scriptToAdd, $page);
$page = str_replace('{cssToAdd}', $cssToAdd, $page);
$page = str_replace('{leftContent}', $leftContent, $page);

//$groups = $currUser->groups;


//$page = str_replace("{groups}", cDrawer::DrawGroupsList($groups), $page);
//$page = str_replace("{info}", "", $page);

echo $page;