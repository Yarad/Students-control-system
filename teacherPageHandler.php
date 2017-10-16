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
    echo '<h2>Список ваших групп</h2>' . cDrawer::DrawGroupsList($currTeacher->groups);

if ($_POST['task'] == "ShowStudents") {
    $index = $_POST['groupID'];
    echo file_get_contents(Constants::$ROOT_PATH . "html_templates/backToGroupsButton.html") . cDrawer::DrawStudentsList($currTeacher->groups[$index]->students);
}

if ($_POST['task'] == "ShowTimetable") {
    $currStudentID = $_POST['studentID'];
    $currGroupID = $_POST['currGroupID'];
    $monthOffset = $_POST['monthOffset'];

    echo '<h2>Расписание студента</h2>' . cDrawer::DrawStudentTimetableToEdit($currTeacher->groups[$currGroupID]->students[$currStudentID], $currTeacher->groups[$currGroupID]->weekTimetable, $monthOffset);
}