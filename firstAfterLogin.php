<?php
//каждый раз придется подсоединяться
include_once "php_classes/cDB.php";
include_once "php_classes/Constants.php";
include_once "php_classes/cDrawer.php";

$db = new cDB();
$currUser = $db->VerifyUser();

if ($currUser == null)
    Constants::authFailed();

$page = file_get_contents("html_templates/teacherMainPage.html");
$groups = $currUser->groups;

//$page = str_replace("{groups}", cDrawer::DrawGroupsList($groups), $page);
//$page = str_replace("{info}", "", $page);

echo $page;