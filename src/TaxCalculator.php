<?php

namespace Paysera;

class TaxCalculator
{
    //cash_in
    const IN_TAX = 0.03;     //Tax percentage
    const MAX_IN_TAX = 5.00; //Max tax for cash_in
    //cash_out
    const NO_TAX = 0.3;
    const MAX_OUT_TAX = 1000.00;    //Natural
    const MAX_CNT = 3;

    const LO_TAX = 0.3;
    const MIN_OUT_TAX = 0.50;       //Legal

    private $cashOutSession;
    private $converter;

    public function __construct(CashOutSession $cashOutSession, Converter $converter)
    {
        $this->cashOutSession = $cashOutSession;
        $this->converter = $converter;
    }

    public function calculateTax(Operation $operation)
    {
        $val = $operation->getAmount();

        if ($operation->getCurrency() != "EUR") {
            //Conversion is neeeded
            $val = $this->converter->convert($val, $operation->getCurrency());
        }

        if ($operation->getType() == "cash_in") {
            $taxToPay = $val * (TaxCalculator::IN_TAX / 100);
            if ($taxToPay > TaxCalculator::MAX_IN_TAX) {
                return TaxCalculator::MAX_IN_TAX;

            } else {
                return $this->converter->convertRound($taxToPay, $operation->getCurrency());
            }

        } elseif ($operation->getType() == "cash_out") {
            if ($operation->getUserType() == "natural") {//Implement conversion

                if ($operation->getCurrency() != "EUR") {
                    $taxToPay = //Converted tax to pay
                        $this->cashOutSession->addToHistory(
                            $operation->getUserId(),
                            $this->converter->convert(
                                $operation->getAmount(),
                                $operation->getCurrency()
                            ),       //Convert the value to EURO for calculation
                            $operation->getDate()
                    ) ;
                        //Returns tax in SPECIFIED CURRENCY
                        $taxToPay = $this->converter->convert(
                            $taxToPay,
                            "EUR",
                            $operation->getCurrency()
                            ) * (TaxCalculator::NO_TAX / 100);

                        return $this->converter->convertRound($taxToPay, $operation->getCurrency());
                    }

                    $taxToPay = $this->cashOutSession->addToHistory(
                        $operation->getUserId(),
                        $operation->getAmount(),
                        $operation->getDate()
                        ) * (TaxCalculator::NO_TAX / 100);

                    return $this->converter->convertRound($taxToPay, "EUR");    //$operation->getOpCurrency() == "EUR";
                }
                //Prideti limitus ir tikrinima
             elseif ($operation->getUserType() == "legal") {
                $taxToPay = $val * (TaxCalculator::LO_TAX / 100);
                if ($taxToPay > TaxCalculator::MIN_OUT_TAX) {
                    return $this->converter->convertRound($taxToPay, $operation->getCurrency());
                } else {
                    $taxToPay = 0;
                    return $taxToPay;
                }
            } else {
                //Fail
            }
        } else {
            //Fail
        }
    }
}
