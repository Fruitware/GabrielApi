<?php

namespace Fruitware\GabrielApi\Model;

interface PassengerInterface extends CustomerInterface
{
    /**
     * Set passenger id, starting from 0
     *
     * @param int $passengerId
     *
     * @return $this
     */
    public function setPassengerId($passengerId);

    /**
     * @return int
     */
    public function getPassengerId();
}