<?php

namespace Paysera\Operations;

use Money\Money;

class Operation
{
    private $date;
    private $userId;
    private $userType;
    private $type;
    private $money;

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @param mixed $userType
     */
    public function setUserType($userType)
    {
        $this->userType = $userType;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @param Money $money
     */
    public function setMoney(Money $money)
    {
        $this->money = $money;
    }

    public function getMoney(): Money
    {
        return $this->money;
    }

    public function getCurrency()
    {
        return $this->money->getCurrency();
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

    public function getDate(): \DateTime
    {
        return $this->date;
    }
}
