<?php
//каждый раз придется подсоединяться
include_once "php_classes/cDB.php";
include_once "php_classes/Constants.php";
include_once "php_classes/cDrawer.php";

setcookie("teacherUnderAdmin","",0,"/");

$db = new cDB();
$currUser = $db->VerifyUser();

if ($currUser == null)
    Constants::authFailed();

$page = file_get_contents("html_templates/userMainPage.html");

$scriptToAdd = '';
$cssToAdd = '';
$leftContent = '';
$extraContent = '';
$right1InitContent = '';
$right2InitContent = '';

$scriptToAdd .= '<script type="application/javascript" src="js/commonPageHandlers.js"></script>';
$scriptToAdd .= '<script type="application/javascript" src="js/cookie.js"></script>';
$extraContent .= file_get_contents('html_templates/settingsWindow.html');

if ($currUser instanceof cTeacher) {
    $cssToAdd .= '<link rel="stylesheet" href="css/teacher.css">';
    $scriptToAdd .= '<script type="application/javascript" src="js/teacherPageHandlers.js"></script>';
	$right1InitContent = '<h2>Список ваших групп</h2>' . cDrawer::DrawGroupsList($currUser->groups);
}

if ($currUser instanceof cStudent) {
    $cssToAdd .= '<link rel="stylesheet" href="css/student.css">';
    $leftContent .= cDrawer::DrawTimetableHeader(Constants::getMonthNameByOffset(0), Constants::getYeraNumByOffset(0), false) . cDrawer::DrawStudentTimetableToEdit($currUser, $db->LoadGroupByID($currUser->groupID, false)->weekTimetable, 0, false);
    $scriptToAdd .= '<script type="application/javascript" src="js/studentPageHandlers.js"></script>';
    $scriptToAdd .= "<script> currGroupID = '" . $currUser->groupID . "';  currStudentID = '" . $currUser->nickName . "';</script>";
    $scriptToAdd .= '<script>document.addEventListener("DOMContentLoaded", onPageLoad);</script>';
}

if ($currUser instanceof cAdmin) {
    $cssToAdd .= '<link rel="stylesheet" href="css/admin.css">';
    $scriptToAdd .= '<script type="application/javascript" src="js/adminPageHandlers.js"></script>';
    $scriptToAdd .= '<script type="application/javascript" src="js/teacherPageHandlers.js"></script>';
    $scriptToAdd .= '<script>document.addEventListener("DOMContentLoaded", onAdminPageLoad);</script>';
    $right1InitContent .= cDrawer::DrawListOfTeachersHeader();
    $right1InitContent .= cDrawer::DrawTeachersList($db->LoadAllTeachers());
}

$page = str_replace('{scriptToAdd}', $scriptToAdd, $page);
$page = str_replace('{cssToAdd}', $cssToAdd, $page);
$page = str_replace('{leftContent}', $leftContent, $page);
$page = str_replace('{right1InitContent}', $right1InitContent, $page);
$page = str_replace('{right2InitContent}', $right2InitContent, $page);
$page = str_replace('{extraContent}', $extraContent, $page);

//$groups = $currUser->groups;


//$page = str_replace("{groups}", cDrawer::DrawGroupsList($groups), $page);
//$page = str_replace("{info}", "", $page);

echo $page;