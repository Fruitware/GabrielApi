<?php

namespace Fruitware\GabrielApi;

interface SessionInterface
{
    /**
     * Set the login and password to be used for API requests.
     *
     * @param string $login
     * @param string $password
     */
    public static function setCredentials($login, $password);

    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @return string
     */
    public static function getLogin();

    /**
     * @return string
     */
    public static function getPassword();
}