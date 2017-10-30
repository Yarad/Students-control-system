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
$temp = new cTeacher("nkohan", "amsterdam", "Кохан Надежда");
$temp->addGroup(new cGroup("group1",$temp->nickName,"Чешский А1"));
$temp->groups['group1']->addStudent(new cStudent("oshebichenko", "frankfurt",'group1', "Шебиченко Ольга"));
$temp->groups['group1']->addStudent(new cStudent("ekirinovich", "frankfurt",'group1', "Киринович Елизавета"));

$db->SaveTeacher($temp);
/*
$temp2 = new cTeacher("amorozova", "copenhagen", "Морозова Алина");
$temp2->addGroup(new cGroup("group2",$temp->nickName,"Чешский А1"));
$temp2->groups['group2']->addStudent(new cStudent("ktraulko", "johannesburg",'group2', "Траулько Карина"));

$temp3 = new cTeacher("vkiruschenko", "wellington", "Кирющенко Вероника");
$temp3->addGroup(new cGroup("group3",$temp->nickName,"Чешский А1.1"));
$temp3->groups['group3']->addStudent(new cStudent("dvarvuh", "johannesburg",'group3', "Варвух Даниил"));
$temp3->groups['group3']->addStudent(new cStudent("kmorgovskaya", "singapore",'group3', "Морговская Ксения"));
$temp3->groups['group3']->addStudent(new cStudent("tkoshepalov", "vienna",'group3', "Кошелапов Тимофей"));
$temp3->groups['group3']->addStudent(new cStudent("vmanulik", "jakarta",'group3', "Манулик Владислав"));
$temp3->groups['group3']->addStudent(new cStudent("iyaroshevich", "cambridge",'group3', "Ярошевич Иван"));
$temp3->groups['group3']->addStudent(new cStudent("akozlova", "marijampol",'group3', "Козлова Анна"));
$temp3->groups['group3']->addStudent(new cStudent("ashibut", "gaborone",'group3', "Шибут Алина"));

$temp3->addGroup(new cGroup("group4",$temp->nickName,"Чешский А2.1 (группа 1)"));
$temp3->groups['group4']->addStudent(new cStudent("alokonina", "gaborone",'group4', "Луконина Анна"));
$temp3->groups['group4']->addStudent(new cStudent("isemenovich", "gaborone",'group4', "Семенович Ирина"));

$temp3->addGroup(new cGroup("group5",$temp->nickName,"Чешский А2.1 (группа 2)"));
$temp3->groups['group5']->addStudent(new cStudent("afokin", "gaborone",'group4', "Фокин Артём"));
$temp3->groups['group5']->addStudent(new cStudent("jhripko", "gaborone",'group4', "Хрипко Юлиана"));

$temp4 = new cTeacher("ayavdoshina", "wellington", "Явдошина Алеся");
$temp4->addGroup(new cGroup("group6",$temp->nickName,"Чешский А1.1 (группа 1)"));
$temp4->groups['group6']->addStudent(new cStudent("psaut", "jakarta",'group6', "Саут Полина"));
$temp4->groups['group6']->addStudent(new cStudent("ekasennikova", "cambridge",'group6', "Казеннова Елизавета"));
$temp4->groups['group6']->addStudent(new cStudent("vchernook", "marijampol",'group6', "Черноок Василина"));
$temp4->groups['group6']->addStudent(new cStudent("sbalvanovich", "gaborone",'group6', "Балванович Стефан"));

$temp4->addGroup(new cGroup("group7",$temp->nickName,"Чешский А1.1 (группа 2)"));
$temp4->groups['group7']->addStudent(new cStudent("psaut", "jakarta",'group7', "Соболь Виктория"));
$temp4->groups['group7']->addStudent(new cStudent("mlyalkovskaya", "jakarta",'group7', "Ляльковская Марта"));


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

