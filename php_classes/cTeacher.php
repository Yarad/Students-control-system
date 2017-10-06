<?php
include_once "cGroup.php";
include_once "cUser.php";
/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 20:07
 */

class cTeacher
{
    public $groups;
    public function addGroup($groupID, $groupInfo = "")
    {
        if (isset($this->groups[$groupID]))
            return false;
        else
        {
            $this->groups[$groupID] = new cGroup();
            return false;
        }

    }
}