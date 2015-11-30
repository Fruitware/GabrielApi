<?php

namespace Fruitware\GabrielApi\Model;

class Passenger extends Customer implements PassengerInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var int
     */
    protected $passengerId;

    /**
     * @var string ISO 3166-2
     */
    protected $nationality;

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
     * @return array
     */
    static public function getTypes()
    {
        return [
            static::TYPE_ADULT,
            static::TYPE_CHILD,
            static::TYPE_INFANT,
        ];
    }

    /**
     * @return array
     */
    static public function getTitles()
    {
        return array_merge([
            static::TITLE_CHD,
            static::TITLE_INF,
        ], parent::getTitles());
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
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

    /**
     * @param $nationality
     *
     * @return $this
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }
}