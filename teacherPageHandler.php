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
    DrawCurrentGroupBlock($currTeacher, $index);
}

if ($_POST['task'] == "ShowTimetable") {
    $currStudentID = $_POST['studentID'];
    $currGroupID = $_POST['currGroupID'];
    $monthOffset = $_POST['monthOffset'];

    echo cDrawer::DrawTimetableHeader(Constants::getMonthNameByOffset($monthOffset), Constants::getYeraNumByOffset($monthOffset), true);
    echo cDrawer::DrawStudentTimetableToEdit($currTeacher->groups[$currGroupID]->students[$currStudentID], $currTeacher->groups[$currGroupID]->weekTimetable, $monthOffset);
}

if ($_POST['task'] == "SaveNotesAndMarks") {
    $monthOffset = $_POST["monthOffset"];
    $newNotesAndMarks = json_decode($_POST["notesAndMarks"]);
    $currStudentID = $_POST['studentID'];
    $currGroupID = $_POST['currGroupID'];

    //добавление оценки
    if ($currStudentID != "ALL_STUDENTS") {
        foreach ($newNotesAndMarks as $key => $value) {
            $currTeacher->groups[$currGroupID]->students[$currStudentID]->editMark($key, new сOneDayRecord($value[0], $value[1]));
        }

        $db->UpdateStudent($currTeacher->groups[$currGroupID]->students[$currStudentID]);
    } else {
        foreach ($currTeacher->groups[$currGroupID]->students as $currStudent) {
            foreach ($newNotesAndMarks as $key => $value) {
                $currStudent->editMark($key, new сOneDayRecord($value[0], $value[1]));
                var_dump($newNotesAndMarks);
            }

            $db->UpdateStudent($currStudent);
        }
    }
}

if ($_POST['task'] == "ShowCommonTimetable") {
    $currGroupID = $_POST['currGroupID'];
    $monthOffset = $_POST['monthOffset'];

    echo cDrawer::DrawTimetableHeader(Constants::getMonthNameByOffset($monthOffset), Constants::getYeraNumByOffset($monthOffset), true);
    echo cDrawer::DrawEmptyTimetableToEdit($currTeacher->groups[$currGroupID]->weekTimetable, $monthOffset);
}

if ($_POST['task'] == "AddStudent") {
    $currGroupID = $_POST['groupID'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $surname = $_POST['surname'];
    $name = $_POST['name'];

    $t1 = $currGroupID != '';
    $t2 = preg_match("#^[a-zA-Z0-9_]+$#", $login);
    $t3 = preg_match("#^[a-zA-Z0-9_]+$#", $password);

    if ($t1 && $t2 && $t3) {
        $currTeacher->groups[$currGroupID]->addStudent(new cStudent($login, $password, $currGroupID, trim($surname) . ' ' . trim($name)));
        $isAdded = $db->UpdateGroup($currTeacher->groups[$currGroupID]);
        $isAdded = $isAdded && $db->SaveStudent($currTeacher->groups[$currGroupID]->students[$login]);
        if ($isAdded)
            DrawCurrentGroupBlock($currTeacher, $currGroupID);
        else
            echo 'DB_ERROR';
    } else {
        echo 'INPUT_ERROR';
    }
}

if ($_POST['task'] == "DeleteStudent") {
    $currGroupID = $_POST['groupID'];
    $login = $_POST['nick'];
    //cDB удаление студента
    $student = $db->LoadStudentByNickName($login);
    $group = $db->LoadGroupByID($student->groupID);

}


function DrawCurrentGroupBlock($teacher, $groupID)
{
    echo file_get_contents(Constants::$ROOT_PATH . "html_templates/backToGroupsButton.html");
    echo file_get_contents(Constants::$ROOT_PATH . "html_templates/commonHomeworkButton.html");
    echo file_get_contents(Constants::$ROOT_PATH . "html_templates/addStudentInGroupButton.html");
    echo cDrawer::DrawStudentsList($teacher->groups[$groupID]->students);
    echo file_get_contents(Constants::$ROOT_PATH . "html_templates/editOrAddUserForm.html");
}