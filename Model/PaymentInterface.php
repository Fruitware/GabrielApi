<?php

namespace Fruitware\GabrielApi\Model;

interface PaymentInterface
{
    const TYPE_CASH = 'CA';
    const TYPE_CREDIT_CARD = 'CC';
    const TYPE_INVOICE = 'IN';
}