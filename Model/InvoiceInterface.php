<?php

namespace Fruitware\GabrielApi\Model;

use DateTime;

interface InvoiceInterface
{
    /**
     * @param array $data
     */
    public function __construct(array $data);

    /**
     * @param string $field
     */
    public function get($field);

    /**
     * @return bool
     */
    public function isExpired();

    /**
     * @return bool
     */
    public function isPaid();

    /**
     * @return bool
     */
    public function isUnpaid();

    /**
     * @return DateTime
     */
    public function getExpiredAt();

    /**
     * @return float
     */
    public function getAmount();

    /**
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @return string
     */
    public function getNumber();

    /**
     * @return string
     */
    public function getStatus();
}