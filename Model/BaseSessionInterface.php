<?php

namespace Fruitware\GabrielApi\Model;

interface BaseSessionInterface
{
    /**
     * Set the login and password to be used for API requests.
     *
     * @param string $login
     * @param string $password
     */
    public static function setCredentials($login, $password);

    /**
     * @return string
     */
    public static function getLogin();

    /**
     * @return string
     */
    public static function getPassword();
}