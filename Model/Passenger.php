<?php

namespace Fruitware\GabrielApi\Model;

class Passenger extends Customer implements PassengerInterface
{
    /**
     * @var int
     */
    protected $passengerId;

    /**
     * @return array
     */
    public function toCustomerArray()
    {
        return [
            'passenger_id' => $this->getPassengerId(),
        ];
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array_merge(parent::toArray(), $this->toCustomerArray());
    }

    /**
     * @param int $passengerId
     *
     * @return $this
     */
    public function setPassengerId($passengerId)
    {
        $this->passengerId = (int)$passengerId;

        return $this;
    }

    /**
     * @return int
     */
    public function getPassengerId()
    {
        return $this->passengerId;
    }
}