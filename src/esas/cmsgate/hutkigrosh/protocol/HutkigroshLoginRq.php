<?php
/**
 * Created by PhpStorm.
 * User: nikit
 * Date: 28.02.2018
 * Time: 12:24
 */

namespace esas\cmsgate\hutkigrosh\protocol;


class HutkigroshLoginRq extends HutkigroshRq
{
    private $username;
    private $password;

    /**
     * HutkigroshLoginRq constructor.
     * @param $username
     * @param $password
     */
    public function __construct($username, $password)
    {
        parent::__construct();
        $this->username = $username;
        $this->password = $password;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = trim($username);
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = trim($password);
    }

}