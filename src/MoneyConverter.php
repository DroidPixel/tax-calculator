<?php

namespace Paysera;

use Money\Money;
use Money\Converter;
use Money\Currency;

class MoneyConverter
{
    private $converter;

    public function __construct(Converter $converter)
    {
        $this->converter = $converter;
    }

    public function convert(Money $amount, Currency $to)
    {
        if ($amount->getCurrency() !== $to) {
            $amount = $this->converter->convert($amount, $to);
        }

        return $amount;
    }
}
