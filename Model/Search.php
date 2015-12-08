<?php

namespace Fruitware\GabrielApi\Model;

class Search implements SearchInterface
{
    /**
     * @var string
     */
    protected $lang;

    /**
     * @var string
     */
    protected $type = self::TYPE_ROUND_TRIP;

    /**
     * @var string
     */
    protected $departureAirport;

    /**
     * @var string
     */
    protected $arrivalAirport;

    /**
     * @var \DateTime
     */
    protected $departureDate;

    /**
     * @var \DateTime
     */
    protected $returnDate;

    /**
     * @var int
     */
    protected $adults = 1;

    /**
     * @var int
     */
    protected $children = 0;

    /**
     * @var int
     */
    protected $infants = 0;

    /**
     * @var int
     */
    protected $searchOption = 1;

    /**
     * @var bool
     */
    protected $directSearch = false;

    /**
     * @inheritdoc
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @inheritdoc
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @inheritdoc
     */
    static public function getTypes()
    {
        return [
            static::TYPE_ONE_WAY,
            static::TYPE_ROUND_TRIP,
        ];
    }

    /**
     * @inheritdoc
     */
    public function setDepartureAirport($departureAirport)
    {
        $this->departureAirport = $departureAirport;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }

    /**
     * @inheritdoc
     */
    public function setArrivalAirport($arrivalAirport)
    {
        $this->arrivalAirport = $arrivalAirport;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getArrivalAirport()
    {
        return $this->arrivalAirport;
    }

    /**
     * @inheritdoc
     */
    public function setDepartureDate(\DateTime $departureDate)
    {
        $this->departureDate = $departureDate->setTime(0, 0, 0);

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * @inheritdoc
     */
    public function setReturnDate(\DateTime $returnDate = null)
    {
        $this->returnDate = $returnDate ? $returnDate->setTime(0, 0, 0) : null;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * @inheritdoc
     */
    public function setAdults($adults)
    {
        $this->adults = (int)$adults;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * @inheritdoc
     */
    public function setChildren($children)
    {
        $this->children = (int)$children;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @inheritdoc
     */
    public function setInfants($infants)
    {
        $this->infants = (int)$infants;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getInfants()
    {
        return $this->infants;
    }

    /**
     * @inheritdoc
     */
    public function setSearchOption($searchOption)
    {
        $this->searchOption = $searchOption;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getSearchOption()
    {
        return $this->searchOption;
    }

    /**
     * @inheritdoc
     */
    public function setDirectSearch($directSearch)
    {
        $this->directSearch = $directSearch;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDirectSearch()
    {
        return $this->directSearch;
    }

    /**
     * @inheritdoc
     */
    public function hasAllowedNumberOfPassengers()
    {
        $numberOfPassengers = $this->getAdults() + $this->getChildren();
        if ($numberOfPassengers <= static::MAX_PASSENGERS_NUMBER) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasAllowedNumberOfInfants()
    {
        if ($this->getAdults() >= $this->getInfants()) {
            return true;
        }

        return false;
    }
}