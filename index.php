<?php
include_once "php_classes/cTeacher.php";
include_once "php_classes/cDB.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:43
 */

$db = new cDB();
/*
$temp = new cTeacher("teacher", "password", "Onoshecko");
$temp->addGroup(new cGroup("group1",$temp->nickName));
$temp->groups['group1']->addStudent(new cStudent("stud1", "stud_password",'group1', "Zhlobich Andrew"));
$temp->groups['group1']->weekTimetable->addLessonPerWeek(1, "15.20");
$temp->groups['group1']->weekTimetable->addLessonPerWeek(3, "15.20");
$db->SaveTeacher($temp);
/**/
//просто потестить

//$retUser = $db->LogIn('teacher','password',$temp);


$loginError = "";

//$db->LoadTeacherByNickName("teacher");


if ($db->VerifyUser() != null) {
    header("Location: firstAfterLogin.php");
    exit();
}

if (isset($_POST["login"]) && isset($_POST["password"])) {
    //пароль введен
    $nick = $_POST["login"];
    $password = $_POST["password"];
    $currUser = $db->LogIn($nick, $password, $out);
    if ($currUser != null) {
        header("Location: firstAfterLogin.php");
        exit();
    } else {
        $loginError = "<p id='ErrorMessage'>Пользователь не найден</p>";
    }
}
//пароль не ввели
$page = file_get_contents("html_templates/login.html");
$page = str_replace("{loginError}", $loginError, $page);
echo $page;

