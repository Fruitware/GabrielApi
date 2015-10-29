<?php

namespace Fruitware\GabrielApi\Gabriel;

use Fruitware\GabrielApi\Model\BaseSessionInterface;

interface SessionInterface extends BaseSessionInterface
{
    /**
     * @param string $token
     */
    public function setToken($token);

    /**
     * @return string
     */
    public function getToken();
}