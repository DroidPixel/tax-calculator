<?php



class Operation
{
    private $opDate;
    private $userId = 0;
    private $userType = "natural"; //Natural = 0, legal = 1
    private $opType = "cash_in"; //cash_in = 0, cash_out = 1;
    private $opValue = 0;
    private $opCurrency = "EUR"; //EUR, USD, JPY

    public function __construct($opDate, $userId ,$userType, $opType, $opValue, $opCurrency)
    {
        $this->opDate = $opDate;
        $this->userId = $userId;
        $this->userType = $userType;
        $this->opType = $opType;
        $this->opValue = $opValue;
        $this->opCurrency = $opCurrency;
    }

    public function getOpValue(){
        return $this->opValue;
    }

    public function getOpType(){
        return $this->opType;
    }

    public function getUserId(){
        return $this->userId;
    }

    public function getUserType(): string
    {
        return $this->userType;
    }

    public function getOpCurrency(): string
    {
        return $this->opCurrency;
    }

    public function getOpDate()
    {
        return $this->opDate;
    }
}