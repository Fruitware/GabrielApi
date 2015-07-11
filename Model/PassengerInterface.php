<?php

namespace Fruitware\GabrielApi\Model;

interface PassengerInterface extends CustomerInterface
{
    /**
     * One of the passengers can be the customer, if you already call setPassengers method
     *
     * @return array
     */
    public function toCustomerArray();

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