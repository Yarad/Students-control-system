<?php
include_once "php_classes/cTeacher.php";
include_once "php_classes/cDB.php";
include_once "php_classes/cAdmin.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:43
 */
 
$db = new cDB();
$loginError = "";


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
