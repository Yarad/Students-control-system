<?php
include_once "php_classes/cTeacher.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:43
 */

echo $_SERVER['DOCUMENT_ROOT'];
$test = new cTeacher("teacher_nick","smth");
$test->addGroup("15-16 years old");
$test->groups["15-16 years old"] -> addStudent(new cStudent("yarad","yarad"));
$test->Save();
var_dump($test->groups["15-16 years old"]);