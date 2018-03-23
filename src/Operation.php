<?php

namespace Paysera;

use Money\Currency;
use Money\Money;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;

class Operation
{
    private $date;
    private $userId;
    private $userType;
    private $type;
    private $money;

    public function __construct($date, $userId , $userType, $type, Money $money)
    {
        $this->date = $date;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->type = $type;
        $this->money = $money;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getCurrency()
    {
        return $this->money->getCurrency()->getCode();
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getDate(): string
    {
        return $this->date;
    }
}
