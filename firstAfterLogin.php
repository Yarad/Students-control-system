<?php
//каждый раз придется подсоединяться
include_once "php_classes/cDB.php";
include_once "php_classes/Constants.php";


$db = new cDB();

if(!$db->VerifyUser())
    Constants::authFailed();

echo "its ok";
