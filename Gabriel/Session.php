<?php

namespace Fruitware\GabrielApi\Gabriel;

use Fruitware\GabrielApi\Model\BaseSession;

class Session extends BaseSession implements SessionInterface
{
    /**
     * @var string
     */
    protected static $login;

    /**
     * @var string
     */
    protected static $password;

    /**
     * @var string
     */
    protected $token;

    /**
     * @param null|string $token Active session token
     */
    public function __construct($token = null)
    {
        $this->setToken($token);
    }

    /**
     * Set the login and password to be used for API requests.
     *
     * @param string $login
     * @param string $password
     */
    public static function setCredentials($login, $password)
    {
        self::$login = $login;
        self::$password = $password;
    }

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public static function getLogin()
    {
        return self::$login;
    }

    /**
     * @return string
     */
    public static function getPassword()
    {
        return self::$password;
    }
}