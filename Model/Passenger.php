<?php

namespace Fruitware\GabrielApi\Model;

class Passenger extends Customer implements PassengerInterface
{
    /**
     * @var int
     */
    protected $passengerId;

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'passenger_id' => $this->getPassengerId(),
        ]);
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