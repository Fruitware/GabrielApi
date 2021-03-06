<?php

namespace Fruitware\GabrielApi\Model;

interface SearchInterface
{
    const LANG_RU = 'ru';
    const LANG_RO = 'ro';
    const LANG_EN = 'en';

    const TYPE_ONE_WAY = 'oneway';
    const TYPE_ROUND_TRIP = 'roundtrip';

    const MAX_PASSENGERS_NUMBER = 9;

    /**
     * Set language ru|en|ro
     *
     * @param string $lang
     *
     * @return $this
     */
    public function setLang($lang);

    /**
     * @return string
     */
    public function getLang();

    /**
     * Set type
     *
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
     * @return array
     */
    static public function getTypes();

    /**
     * Set departure airport/city
     *
     * @param string $departureAirport
     *
     * @return $this
     */
    public function setDepartureAirport($departureAirport);

    /**
     * @return string
     */
    public function getDepartureAirport();

    /**
     * Set arrival airport/city
     *
     * @param string $arrivalAirport
     *
     * @return $this
     */
    public function setArrivalAirport($arrivalAirport);

    /**
     * @return string
     */
    public function getArrivalAirport();

    /**
     * Set departure date
     *
     * @param \DateTime  $departureDate
     *
     * @return $this
     */
    public function setDepartureDate(\DateTime $departureDate);

    /**
     * @return \DateTime
     */
    public function getDepartureDate();

    /**
     * Set return date
     *
     * @param \DateTime $returnDate
     *
     * @return $this
     */
    public function setReturnDate(\DateTime $returnDate = null);

    /**
     * @return \DateTime
     */
    public function getReturnDate();

    /**
     * Set number of adults
     *
     * @param int $adults
     *
     * @return $this
     */
    public function setAdults($adults);

    /**
     * @return int
     */
    public function getAdults();

    /**
     * Set number of children (2 -12 years)
     *
     * @param int $children
     *
     * @return $this
     */
    public function setChildren($children);

    /**
     * @return int
     */
    public function getChildren();

    /**
     * Set number of infants (under 2 years)
     *
     * @param int $infants
     *
     * @return $this
     */
    public function setInfants($infants);

    /**
     * @return int
     */
    public function getInfants();

    /**
     * Set search identifier – ability to have more than one active searches
     *
     * @param int $searchOption
     *
     * @return $this
     */
    public function setSearchOption($searchOption);

    /**
     * @return int
     */
    public function getSearchOption();

    /**
     * Ignore cached data of available segments. Default is false

     * @param bool $directSearch
     *
     * @return $this
     */
    public function setDirectSearch($directSearch);

    /**
     * @return bool
     */
    public function getDirectSearch();

    /**
     * @return bool
     */
    public function hasAllowedNumberOfPassengers();

    /**
     * @return bool
     */
    public function hasAllowedNumberOfInfants();
}