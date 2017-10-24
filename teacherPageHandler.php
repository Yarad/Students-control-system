<?php
include_once "php_classes/cDB.php";
include_once "php_classes/cDrawer.php";
include_once "php_classes/сOneDayRecord.php";
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

    echo cDrawer::DrawStudentTimetableHeader(Constants::getMonthNameByOffset($monthOffset), Constants::getYeraNumByOffset($monthOffset)) . cDrawer::DrawStudentTimetableToEdit($currTeacher->groups[$currGroupID]->students[$currStudentID], $currTeacher->groups[$currGroupID]->weekTimetable, $monthOffset);
}

if ($_POST['task'] == "SaveNotesAndMarks") {
    $monthOffset = $_POST["monthOffset"];
    $newNotesAndMarks = json_decode($_POST["notesAndMarks"]);
    $currStudentID = $_POST['studentID'];
    $currGroupID = $_POST['currGroupID'];

    //добавление оценки

    foreach ($newNotesAndMarks as $key => $value) {
        $currTeacher->groups[$currGroupID]->students[$currStudentID]->editMark($key,new сOneDayRecord($value[0],$value[1]));
    }


    $db->UpdateStudent($currTeacher->groups[$currGroupID]->students[$currStudentID]);
}