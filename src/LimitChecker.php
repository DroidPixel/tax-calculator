<?php

namespace Paysera;

use Money\Money;
use Paysera\Tax\TaxLimit;

class LimitChecker
{
    public function checkAgainstLimit(Money $amount, TaxLimit $taxLimit): Money
    {
        if ($taxLimit->getMaxTax()->getAmount() !== '0') {
            if ($amount->greaterThanOrEqual($taxLimit->getMaxTax())) {
                return $taxLimit->getMaxTax();
            }
        } elseif ($taxLimit->getMinTax()->getAmount() !== '0') {
            if ($amount->lessThanOrEqual($taxLimit->getMinTax())) {
                return new Money(0, $amount->getCurrency());
            }
        }

        return $amount;
    }
}
