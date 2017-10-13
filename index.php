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
/*$temp = new cTeacher("teacher","password");
$temp->addGroup("group1");
$temp->groups['group1']->addStudent(new cStudent("stud1","stud_password"));

$db->SaveTeacher($temp);
*/

//$retUser = $db->LogIn('teacher','password',$temp);

if(!$db->VerifyUser()) {
    echo file_get_contents("html_templates/login.html");
}else{
    header ('firstAfterLogin.php');  // перенаправление на нужную страницу
    exit();    // прерываем работу скрипта, чтобы забыл о прошлом
}


