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

if ($currUser instanceof cTeacher) {
    $scriptToAdd .= '<script type="application/javascript" src="js/teacherPageHandlers.js"></script>';
    $cssToAdd.='<link rel="stylesheet" href="css/teacher.css">';

}

if ($currUser instanceof cStudent) {
    $cssToAdd .= '<link rel="stylesheet" href="css/student.css">';
}

$page = str_replace('{scriptToAdd}',$scriptToAdd,$page);
$page = str_replace('{cssToAdd}',$cssToAdd,$page);

$groups = $currUser->groups;


//$page = str_replace("{groups}", cDrawer::DrawGroupsList($groups), $page);
//$page = str_replace("{info}", "", $page);

echo $page;