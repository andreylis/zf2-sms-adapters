<?php
/**
 * @author     Andrey Lis <me@andreylis.ru>
 */

namespace SMSSender\Service;


use Zend\Stdlib\AbstractOptions;

class Options extends AbstractOptions
{

    protected $provider;

    protected $username;

    protected $password;

    protected $sender;

    /**
     * Enable default entity
     *
     * @var bool
     */
    protected $enableEntity = false;

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $provider
     */
    public function setProvider($provider)
    {
        $this->provider = $provider;
    }

    /**
     * @return mixed
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param boolean $enableEntity
     */
    public function setEnableEntity($enableEntity)
    {
        $this->enableEntity = $enableEntity;
    }

    /**
     * @return boolean
     */
    public function getEnableEntity()
    {
        return $this->enableEntity;
    }


}