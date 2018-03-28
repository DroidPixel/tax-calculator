<?php

namespace Paysera;

use Money\Money;

class TaxCalculator
{
    public function calculateTax(Money $tax, float $percentage, $roundMode)
    {
        return $tax->multiply($percentage, $roundMode);
    }
}
