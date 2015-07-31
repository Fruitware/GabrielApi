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
     * @param string $lang
     *
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param string $departureAirport
     *
     * @return $this
     */
    public function setDepartureAirport($departureAirport)
    {
        $this->departureAirport = $departureAirport;

        return $this;
    }

    /**
     * @return string
     */
    public function getDepartureAirport()
    {
        return $this->departureAirport;
    }

    /**
     * @param string $arrivalAirport
     *
     * @return $this
     */
    public function setArrivalAirport($arrivalAirport)
    {
        $this->arrivalAirport = $arrivalAirport;

        return $this;
    }

    /**
     * @return string
     */
    public function getArrivalAirport()
    {
        return $this->arrivalAirport;
    }

    /**
     * @param \DateTime  $departureDate
     *
     * @return $this
     */
    public function setDepartureDate(\DateTime $departureDate)
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDepartureDate()
    {
        return $this->departureDate;
    }

    /**
     * @param \DateTime $returnDate
     *
     * @return $this
     */
    public function setReturnDate(\DateTime $returnDate = null)
    {
        $this->returnDate = $returnDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getReturnDate()
    {
        return $this->returnDate;
    }

    /**
     * @param int $adults
     *
     * @return $this
     */
    public function setAdults($adults)
    {
        $this->adults = (int)$adults;

        return $this;
    }

    /**
     * @return int
     */
    public function getAdults()
    {
        return $this->adults;
    }

    /**
     * @param int $children
     *
     * @return $this
     */
    public function setChildren($children)
    {
        $this->children = (int)$children;

        return $this;
    }

    /**
     * @return int
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param int $infants
     *
     * @return $this
     */
    public function setInfants($infants)
    {
        $this->infants = (int)$infants;

        return $this;
    }

    /**
     * @return int
     */
    public function getInfants()
    {
        return $this->infants;
    }

    /**
     * @param int $searchOption
     *
     * @return $this
     */
    public function setSearchOption($searchOption)
    {
        $this->searchOption = $searchOption;

        return $this;
    }

    /**
     * @return int
     */
    public function getSearchOption()
    {
        return $this->searchOption;
    }

    /**
     * @param bool $directSearch
     *
     * @return $this
     */
    public function setDirectSearch($directSearch)
    {
        $this->directSearch = $directSearch;

        return $this;
    }

    /**
     * @return int
     */
    public function getDirectSearch()
    {
        return $this->directSearch;
    }
}