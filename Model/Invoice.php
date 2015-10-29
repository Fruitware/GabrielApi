<?php

namespace Fruitware\GabrielApi\Model;

use DateTime;

class Invoice implements InvoiceInterface
{
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
    }

    /**
     * @param string $field
     */
    public function get($field)
    {
        return $this->data['Items'][0][$field];
    }

    /**
     * @return DateTime
     */
    public function getExpiredAt()
    {
        return DateTime::createFromFormat('Y-m-d\TH:i:s', $this->get('ExpiryDate'));
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