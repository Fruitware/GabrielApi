<?php

namespace Fruitware\GabrielApi\Model;

abstract class BaseSession implements BaseSessionInterface
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