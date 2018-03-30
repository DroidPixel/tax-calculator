<?php

namespace Paysera;

use Money\Currency;
use Money\Money;
use Paysera\Tax\TaxProvider;

class CashOutSession
{
    private $history;
    private $currency;
    private $maxOutTax;
    private $maxCount;

    public function __construct(Currency $currency, Money $maxOutTax, int $maxCount)
    {
        $this->history = [];
        $this->currency = $currency;
        $this->maxOutTax = $maxOutTax;
        $this->maxCount = $maxCount;
    }

    public function addToHistory($userId, Money $money, \DateTime $date)
    {
        if (!isset($this->history[$userId])) {
            $this->history[$userId] = $this->setToEntry($money, $date);
            
            return $money->greaterThan($this->maxOutTax) ?
                $money->subtract($this->maxOutTax) : new Money(0, $this->currency);
        } else {
            if ($this->history[$userId]['date']->format('W') === $date->format('W')) {
                if (
                    $this->history[$userId]['count'] < $this->maxCount
                    && $money->add($this->history[$userId]['value'])->lessThan($this->maxOutTax)
                ) {
                    $this->history[$userId]['count']++;
                    $this->addMoneyToHistory($money, $userId);

                    return new Money(0, $this->currency);
                } else {
                    if ($this->maxOutTax->greaterThanOrEqual($this->history[$userId]['value'])) {
                        $toLimit = $this->maxOutTax->subtract($this->history[$userId]['value']);
                        //How much till limit?
                        $this->addMoneyToHistory($money, $userId);

                        return $money->subtract($toLimit);
                    }
                    $this->addMoneyToHistory($money, $userId);

                    return $money;
                }
            } else {
                //Different week, reset count and amount
                $this->history[$userId] = $this->setToEntry($money, $date);

                if ($money->lessThan($this->maxOutTax)) {
                    return new Money(0, $this->currency);
                } else {
                    //Used up the new weeks limit, calculate the amount to apply tax to
                    return $money->subtract($this->maxOutTax);
                }
            }
        }
    }

    private function setToEntry(Money $money, \DateTime $date)
    {
        return [
            'count' => 1,
            'value' => $money,
            'date'  => $date
        ];
    }

    private function addMoneyToHistory($value, $userId)
    {
        $this->history[$userId]['value'] =
            $this->history[$userId]['value']->add($value);
    }

    public function getHistory(): array
    {
        return $this->history;
    }
}
