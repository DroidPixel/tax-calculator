<?php

namespace Paysera;

class Operation
{
    private $Date;
    private $userId;
    private $userType;
    private $Type;
    private $Amount;
    private $Currency; //EUR, USD, JPY

    public function __construct($opDate, $userId ,$userType, $opType, $opAmount, $opCurrency)
    {
        $this->Date = $opDate;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->Type = $opType;
        $this->Amount = $opAmount;
        $this->Currency = $opCurrency;
    }

    public function getAmount(): string
    {
        return $this->Amount;
    }

    public function getType()
    {
        return $this->Type;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getCurrency(): string
    {
        return $this->Currency;
    }

    public function getDate()
    {
        return $this->Date;
    }
}
