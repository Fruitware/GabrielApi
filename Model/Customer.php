<?php

namespace Fruitware\GabrielApi\Model;

class Customer implements CustomerInterface
{
    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $gender;

    /**
     * @var \DateTime
     */
    protected $birthDate;

    /**
     * @var string
     */
    protected $passport;

    /**
     * @var \DateTime
     */
    protected $passportIssued;

    /**
     * @var \DateTime
     */
    protected $passportExpire;

    /**
     * @var string ISO 3166-2
     */
    protected $passportCountry;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $email;

    public function toArray()
    {
        return [
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'title' => $this->getTitle(),
            'gender' => $this->getGender(),
            'birth_date' => $this->getBirthDate()->format('Y-m-d'),
            'Passport' => $this->getPassport(),
            'PassportIssued' => $this->getPassportIssued()->format('Y-m-d'),
            'PassportExpire' => $this->getPassportExpire()->format('Y-m-d'),
            'PassportCountry' => $this->getPassportCountry(),
            'Contact' => $this->getPhone(),
            'Email' => $this->getEmail()
        ];
    }

    /**
     * @return array
     */
    static public function getGenders()
    {
        return [
            static::GENDER_MALE,
            static::GENDER_FEMALE,
        ];
    }

    /**
     * @return array
     */
    static public function getTitles()
    {
        return [
            static::TITLE_MR,
            static::TITLE_MS,
        ];
    }

    /**
     * @param string $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $gender
     *
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param \DateTime $birthDate
     *
     * @return $this
     */
    public function setBirthDate(\DateTime $birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param string $passport
     *
     * @return $this
     */
    public function setPassport($passport)
    {
        $this->passport = $passport;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassport()
    {
        return $this->passport;
    }

    /**
     * @param \DateTime $passportIssued
     *
     * @return $this
     */
    public function setPassportIssued(\DateTime $passportIssued)
    {
        $this->passportIssued = $passportIssued;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPassportIssued()
    {
        return $this->passportIssued;
    }

    /**
     * @param \DateTime $passportExpire
     *
     * @return $this
     */
    public function setPassportExpire(\DateTime $passportExpire)
    {
        $this->passportExpire = $passportExpire;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPassportExpire()
    {
        return $this->passportExpire;
    }

    /**
     * @param string $passportCountry
     *
     * @return $this
     */
    public function setPassportCountry($passportCountry)
    {
        $this->passportCountry = $passportCountry;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassportCountry()
    {
        return $this->passportCountry;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
}