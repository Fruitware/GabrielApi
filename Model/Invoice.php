<?php

namespace Fruitware\GabrielApi\Model;

use DateTime;

class Invoice implements InvoiceInterface
{
    protected $dateTimeZone;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->dateTimeZone = new \DateTimeZone('Europe/Chisinau');
    }

    /**
     * @param string $field
     */
    public function get($field)
    {
        return $this->data['Items'][0][$field];
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        $now = new DateTime('now', $this->dateTimeZone);

        return $this->getExpiredAt() < $now;
    }

    /**
     * @return bool
     */
    public function isPaid()
    {
        return $this->getStatus() === 'Paid';
    }

    /**
     * @return bool
     */
    public function isUnpaid()
    {
        return $this->getStatus() === 'Unpaid';
    }

    /**
     * @return DateTime
     */
    public function getExpiredAt()
    {
        $date = DateTime::createFromFormat('Y-m-d\TH:i:s', $this->get('ExpiryDate'), $this->dateTimeZone);
        // fix api time zone
        return $date->modify('+1 hour');
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return (float)$this->get('InvoiceAmount');
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return (float)$this->get('InvoiceCurrencyCode');
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->get('InvoiceNumber');
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->get('PaymentStateName');
    }
}