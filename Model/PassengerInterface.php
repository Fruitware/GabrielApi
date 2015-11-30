<?php

namespace Fruitware\GabrielApi\Model;

interface PassengerInterface extends CustomerInterface
{
    const TYPE_ADULT = 'adt';
    const TYPE_CHILD = 'chd';
    const TYPE_INFANT = 'inf';

    const TITLE_CHD  = self::TYPE_CHILD;
    const TITLE_INF  = self::TYPE_INFANT;

    /**
     * One of the passengers can be the customer, if you already call setPassengers method
     *
     * @return array
     */
    public function toCustomerArray();

    /**
     * @return array
     */
    static public function getTypes();

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getType();

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