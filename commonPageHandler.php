<?php
include_once "php_classes/cDB.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.10.2017
 * Time: 13:12
 */

$db = new cDB();
$currUser = $db->VerifyUser();

if ($_POST['task'] == "logout")
{
    $db->LogOut($currUser);
    print_r($_COOKIE);
}