<?php
include_once "cGroup.php";
include_once "cUser.php";
include_once "Constants.php";

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:07
 */
class cTeacher extends cUser implements JsonSerializable
{
    public $groups;

    public function addGroup($groupID, $groupInfo = "")
    {
        if (isset($this->groups[$groupID]))
            return false;
        else {
            $t = new cGroup();
            $this->groups[$groupID] = new cGroup();
            return false;
        }
    }

    public function Save()
    {
        $str = json_encode($this);
        echo Constants::$ROOT_PATH . "teachers/";
        file_put_contents(Constants::$ROOT_PATH . "teachers/" . $this->getNickName() . ".json", $str);
    }

    function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}