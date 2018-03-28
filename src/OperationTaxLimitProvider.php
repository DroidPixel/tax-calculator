<?php

namespace Paysera;

use Money\Currency;
use Money\Money;

class OperationTaxLimitProvider
{
    private $currency;

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
            $minTaxAmount = TaxProvider::MIN_OUT_TAX;
        } elseif ($operation->getType() === "cash_in") {
            $maxTaxAmount = TaxProvider::MAX_IN_TAX;
        }

        $taxLimit->setMaxTax(
            new Money($maxTaxAmount, $this->currency)
        )->setMinTax(
            new Money($minTaxAmount, $this->currency)
        );

        return $taxLimit;
    }
}
