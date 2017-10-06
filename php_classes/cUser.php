<?php

/**
 * Created by PhpStorm.
 * User: user
 * Date: 06.10.2017
 * Time: 19:46
 */
abstract class cUser
{
    private $nickName;
    private $passwordHash;
    public $extraInfo;

    public function __construct($nick, $password)
    {
        $this->nickName = $nick;
        $this->passwordHash = $password; //пока что сохраняем просто так

    }

    public function getNickName()
    {
        return $this->nickName;
    }

    public abstract function Save();
}