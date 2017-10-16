<?php
include_once "php_classes/cDB.php";
include_once "php_classes/cDrawer.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.10.2017
 * Time: 1:15
 */
$db = new cDB();
$currTeacher = $db->VerifyUser();

if ($_POST['task'] == "ShowGroups")
    echo cDrawer::DrawGroupsList($currTeacher->groups);
if ($_POST['task'] == "ShowStudents") {
    $index = $_POST['groupID'];
    echo cDrawer::DrawStudentsList($currTeacher->groups[$index]->students);
}