<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 19:46
 */
abstract class cUser implements JsonSerializable
{
    public $nickName;
    public $passwordHash;
    public $extraInfo;

    public function __construct($nick, $password)
    {
        $this->nickName = $nick;
        $this->passwordHash = $password; //пока что сохраняем просто так
        $this->extraInfo = "";
    }

    public abstract function Save();

    function jsonSerialize()
    {
        $array = [];
        foreach ($this as $k => $v) {
            if (is_array($v) || is_object($v)) {
                $array[$k] = array_keys($v);
            } else {
                $array[$k] = $v;
            }
        }
        return $array;
    }

    public function getCurrentHash()
    {
        return $this->passwordHash;
        //временная мера
    }

    public abstract static function JsonDecode($jsonStr);
}