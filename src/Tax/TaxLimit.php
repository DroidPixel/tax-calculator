<?php

namespace Paysera\Tax;

use Money\Money;

class TaxLimit
{
    private $minTax;
    private $maxTax;


    public function getMinTax() : Money
    {
        return $this->minTax;
    }


    public function setMinTax(Money $minTax)
    {
        $this->minTax = $minTax;

        return $this;
    }

    public function getMaxTax() : Money
    {
        return $this->maxTax;
    }


    public function setMaxTax(Money $maxTax)
    {
        $this->maxTax = $maxTax;

        return $this;
    }
}
