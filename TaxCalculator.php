<?php

class TaxCalculator
{
    //cash_in
    private $inTax = 0.03;     //Tax percentage
    private $maxinTax = 5.00; //Max tax for cash_in
    //cash_out
    private $noTax = 0.3;
    private $maxoutTax = 1000.00;    //Natural
    private $maxCnt = 3;

    private $loTax = 0.3;
    private $minoutTax = 0.50;       //Legal

    private $cashOutSession;
    public $converter;

    public function __construct()
    {
        $this->cashOutSession = new CashOutSession();
        $this->converter = new Converter();
    }

    public function calculateTax(Operation $operation)
    {
        $val = $operation->getOpValue();

        if ($operation->getOpCurrency() != "EUR") {
            //Conversion is neeeded
            $val = $this->converter->convert($val, $operation->getOpCurrency());
        }

        if ($operation->getOpType() == "cash_in") {
            $taxToPay = $val * ($this->inTax / 100);
            if ($taxToPay > $this->maxinTax) {
                $taxToPay = $this->maxinTax;

                return $taxToPay;
            } else {
                return $this->converter->convertRound($taxToPay, $operation->getOpCurrency());
            }

        } elseif ($operation->getOpType() == "cash_out") {
            if ($operation->getUserType() == "natural") {//Implement conversion

                    if ($operation->getOpCurrency() != "EUR") {
                        $taxToPay = //Converted tax to pay
                                $this->cashOutSession->addToHistory(
                                    $operation->getUserId(),
                                    $this->converter->convert(
                                        $operation->getOpValue(),
                                        $operation->getOpCurrency()
                                    ),       //Convert the value to EURO for calculation
                                    $operation->getOpDate()
                            ) ;
                        //Returns tax in SPECIFIED CURRENCY
                        $taxToPay = $this->converter->convert(
                            $taxToPay,
                            "EUR",
                            $operation->getOpCurrency()
                            ) * ($this->noTax / 100);
                        return $this->converter->convertRound($taxToPay, $operation->getOpCurrency());
                    }
                    $taxToPay = $this->cashOutSession->addToHistory(
                        $operation->getUserId(),
                        $operation->getOpValue(),
                        $operation->getOpDate()
                        ) * ($this->noTax / 100);
                return $this->converter->convertRound($taxToPay, "EUR");    //$operation->getOpCurrency() == "EUR";
                }
                //Prideti limitus ir tikrinima
             elseif ($operation->getUserType() == "legal") {
                $taxToPay = $val * ($this->loTax / 100);
                if ($taxToPay > 0.50) {
                    return $this->converter->convertRound($taxToPay, $operation->getOpCurrency());
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
