<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.11.2017
 * Time: 2:30
 */
include_once "php_classes/cDB.php";
include_once "php_classes/cDrawer.php";

$db = new cDB();
$currUser = $db->VerifyUser();

if ($_POST['task'] == "ShowTeachers") {
    echo cDrawer::DrawListOfTeachersHeader() . cDrawer::DrawTeachersList($db->LoadAllTeachers());
}
