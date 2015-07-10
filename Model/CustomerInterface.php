<?php

namespace Fruitware\GabrielApi\Model;

interface CustomerInterface
{
    const TITLE_MR  = 'Mr';
    const TITLE_MS  = 'Ms';
    const TITLE_MRS = 'Mrs';
    const TITLE_DR  = 'Dr';

    const GENDER_MALE        = 'M';
    const GENDER_FEMALE      = 'F';
    const GENDER_UNDISCLOSED = 'U';

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName);

    /**
     * @return string
     */
    public function getFirstName();

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName);

    /**
     * @return string
     */
    public function getLastName();

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender);

    /**
     * @return string
     */
    public function getGender();

    /**
     * @param \DateTime $birthDate
     *
     * @return $this
     */
    public function setBirthDate(\DateTime $birthDate);

    /**
     * @return \DateTime
     */
    public function getBirthDate();

    /**
     * @param string $passport
     *
     * @return $this
     */
    public function setPassport($passport);

    /**
     * @return string
     */
    public function getPassport();

    /**
     * @param \DateTime $passportIssued
     *
     * @return $this
     */
    public function setPassportIssued(\DateTime $passportIssued);

    /**
     * @return \DateTime
     */
    public function getPassportIssued();

    /**
     * @param \DateTime $passportExpire
     *
     * @return $this
     */
    public function setPassportExpire(\DateTime $passportExpire);

    /**
     * @return \DateTime
     */
    public function getPassportExpire();

    /**
     * @param string $passportCountry
     *
     * @return $this
     */
    public function setPassportCountry($passportCountry);

    /**
     * @return string
     */
    public function getPassportCountry();

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone);

    /**
     * @return string
     */
    public function getPhone();

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setEmail($phone);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return array
     */
    public function toArray();
}