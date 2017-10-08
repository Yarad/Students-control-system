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

$db->LoadTeacherByNickName("teacher_nick");
//var_dump($test->groups["15-16 years old"]);