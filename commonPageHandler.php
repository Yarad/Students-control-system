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

if ($_POST['task'] == "logout") {
    $db->LogOut($currUser);
    print_r($_COOKIE);
}

if ($_POST['task'] == "SaveSettings") {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    if (password_verify($oldPassword, $currUser->passwordHash)) {
        $t1 = $newPassword != '';
        $t2 = preg_match("#^[a-zA-Z0-9_]+$#", $newPassword);
        if ($t1 && $t2) {
            $currUser->SetNewPassword($newPassword);
            if ($db->UpdateTeacher($currUser)) {
                $nullptr = "";
                $currUser = $db->LogIn($currUser->nickName, $currUser->passwordHash, $nullptr);
                echo "OK";
            } else
                echo "ERROR";
        } else
            echo "ERROR";
    } else
        echo "ERROR";
}