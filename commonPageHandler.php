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
        if ($newPassword != '' && preg_match("#^[a-zA-Z0-9_]+$#", $newPassword)) {
            $currUser->SetNewPassword($newPassword);

            $tempBool = false;
            if ($currUser instanceof cTeacher)
                $tempBool = $db->UpdateTeacher($currUser);
            if ($currUser instanceof cStudent)
                $tempBool = $db->UpdateStudent($currUser);
            if ($currUser instanceof cAdmin)
                $tempBool = $db->UpdateAdmin($currUser);


            if ($tempBool) {
                $nullptr = "";
                $currUser = $db->LogIn($currUser->nickName, $currUser->passwordHash, $nullptr);
                echo "OK";
            } else
                echo "ERROR1";
        } else
            echo "ERROR2";
    } else
        echo "ERROR3" . $currUser->passwordHash; //ошибка здесь
}