<?php

namespace Paysera;

use Money\Currency;
use Money\Money;

class TaxCalculator
{
    //cash_in
    const IN_TAX = 0.0003;
    const MAX_IN_TAX = 500;

    //cash_out
    const NO_TAX = 0.003;
    const MAX_OUT_TAX = 1000.00;
    const MAX_CNT = 3;

    const LO_TAX = 0.003;
    const MIN_OUT_TAX = 0.50;

    private $cashOutSession;
    private $converter;

    public function __construct(CashOutSession $cashOutSession, MoneyConverter $converter)
    {
        $this->cashOutSession = $cashOutSession;
        $this->converter = $converter;
    }

    public function calculateTax(Operation $operation)
    {
        $amount = $operation->getMoney();

        if ($amount->getCurrency() != "EUR") {
            //Conversion is neeeded for operations, convert
            $amount = $this->converter->convert($amount, new Currency("EUR"));
        }

        if ($operation->getType() == "cash_in") {
            $amount = $amount->multiply(TaxCalculator::IN_TAX, Money::ROUND_UP); //Calculate tax

            if (
                $amount->greaterThan(
                    new Money(
                        TaxCalculator::MAX_IN_TAX,
                        new Currency("EUR")
                    )
                )
            ) {
                return $this->converter->convert(
                    new Money(TaxCalculator::MAX_IN_TAX, new Currency("EUR")),
                    new Currency($operation->getCurrency())
                );

            } else {
                return $this->converter->convert(
                    $amount,
                    new Currency($operation->getCurrency())
                );
            }

        } elseif ($operation->getType() == "cash_out") {

            if ($operation->getUserType() == "natural") {

                if ($operation->getCurrency() != "EUR") {
                //IF CURRENCY NEEDS TO BE RECONVERTED BACK TO ORIGINAL
                    $amount = $this->cashOutSession->addToHistory(
                            $operation->getUserId(),
                            $amount,
                            $operation->getDate()
                    );
                        //Returns tax in SPECIFIED CURRENCY
                    return $this->converter->convert(
                        $amount,
                        new Currency($operation->getCurrency())
                    )->multiply(TaxCalculator::NO_TAX, Money::ROUND_UP);
                }
                    //If Currency original
                    return $this->cashOutSession->addToHistory(
                        $operation->getUserId(),
                        $amount,
                        $operation->getDate()
                    )->multiply(TaxCalculator::NO_TAX, Money::ROUND_UP);
            } elseif ($operation->getUserType() == "legal") {
                $amount = $amount->multiply(TaxCalculator::LO_TAX, Money::ROUND_UP);

                if (floatval($amount->getAmount()) > TaxCalculator::MIN_OUT_TAX) {
                    //Bigger than the min, OK
                    return $this->converter->convert(
                        $amount, new Currency($operation->getCurrency())
                    );

                } else {
                    //Smaller than the min, no charge
                    return new Money(0, new Currency("EUR"));
                }
            } else {
                //throw new
            }
        } else {
            //throw new
        }
    }
}
