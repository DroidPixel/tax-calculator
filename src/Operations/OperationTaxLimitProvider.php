<?php

namespace Paysera\Operations;

use Money\Currency;
use Money\Money;
use Paysera\Tax\TaxLimit;
use Paysera\Tax\TaxPercentage;

class OperationTaxLimitProvider
{
    private $currency;
    private $percentager;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }

    public function getTaxLimit(Operation $operation)
    {
        $taxLimit = new TaxLimit();
        $minTaxAmount = 0;
        $maxTaxAmount = 0;

        if ($operation->getType() === "cash_out" && $operation->getUserType() === "legal") {
            $minTaxAmount = $this->percentager->getMinimumOutputTax();
        } elseif ($operation->getType() === "cash_in") {
            $maxTaxAmount = $this->percentager->getMaximumInputTax();
        }

        $taxLimit->setMaxTax(
            new Money($maxTaxAmount, $this->currency)
        )->setMinTax(
            new Money($minTaxAmount, $this->currency)
        );

        return $taxLimit;
    }
}
