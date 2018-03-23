<?php

namespace Paysera;

use Money\Currency;
use Money\Money;

class CashOutSession
{
    private $history;
    private $currency;

    public function __construct(Currency $currency)
    {
        $this->history = [];
        $this->currency = $currency;
    }

    public function addToHistory($userId, Money $money, $date)
    {
        //Pas via constructor
        $maxOutTax = new Money(TaxCalculator::MAX_OUT_TAX * 100, $this->currency);

        if (!isset($this->history[$userId])) {
            $this->history[$userId] = $this->resetHistory($money, $date);

            if ($money->greaterThan($maxOutTax)) {
                return $money->subtract($maxOutTax);
            } else {
                return new Money(0, $this->currency);
            }

        } else { //$userId exists
            if ($this->history[$userId]["date"] == date("W",strtotime($date))) {

                if (
                    $this->history[$userId]["count"] < TaxCalculator::MAX_CNT
                    && $money->add($this->history[$userId]["value"])->lessThan($maxOutTax)
                ) {

                    $this->history[$userId]["count"]++;
                    $this->valToHistory($money, $userId);

                    return new Money(0, $this->currency);
                } else {
                    //Count => 3 or Amount > 1000, therefore apply additional cost
                    if ($maxOutTax->greaterThanOrEqual($this->history[$userId]["value"])) {
                        $toLimit = $maxOutTax->subtract($this->history[$userId]["value"]);
                        //How much till limit?
                        $this->valToHistory($money, $userId);

                        return $money->subtract($toLimit);
                    }
                    $this->valToHistory($money, $userId);

                    return $money;
                }
            } else {
                //Different week, reset count and amount
                $this->history[$userId] = $this->resetHistory($money, $date);

                if ($money->lessThan($maxOutTax)) {
                    return new Money(0, $this->currency);
                } else {
                    //Used up the new weeks limit, calculate the amount to apply tax to
                    return $money->subtract($maxOutTax);
                }
            }
        }
    }

    private function resetHistory($money, $date)
    {
        return [
            "count" => 1,
            "value" => $money,
            "date"  => date("W",strtotime($date))
    ];
    }

    private function valToHistory($value, $userId)
    {
        $this->history[$userId]["value"] =
            $this->history[$userId]["value"]->add($value);
    }

    public function getHistory(): array
    {
        return $this->history;
    }

}
