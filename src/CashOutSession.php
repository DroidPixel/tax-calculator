<?php

namespace Paysera;

class CashOutSession
{
    private $history;

    public function __construct()
    {
        $this->history = [];
    }

    public function addToHistory($userId, $opValue, $opDate)
    {
        if (!isset($this->history[$userId])) {
            $this->history[$userId] = [
                "count" => 1,
                "value" => $opValue,
                "date"  => date("W",strtotime($opDate))
            ];

            if ($opValue > 1000) {
                return $opValue - 1000;
            } else {
                return 0;
            }

        } else { //$userId exists
            if ($this->history[$userId]["date"] == date("W",strtotime($opDate))) {

                if (
                    $this->history[$userId]["count"] < TaxCalculator::MAX_CNT
                    && ($this->history[$userId]["value"] + $opValue) < TaxCalculator::MAX_OUT_TAX
                ) {

                    $this->history[$userId]["count"]++;
                    $this->history[$userId]["value"] += $opValue;

                    return 0;
                } else {
                    //Count => 3 or Amount > 1000, therefore apply additional cost
                    if ($this->history[$userId]["value"] <= TaxCalculator::MAX_OUT_TAX) {
                        $toLimit = TaxCalculator::MAX_OUT_TAX - $this->history[$userId]["value"];
                        //How much till limit?
                        $this->history[$userId]["value"] += $opValue;
                        return $opValue - $toLimit;
                    }
                    $this->history[$userId]["value"] += $opValue;;
                    return $opValue;
                }
            } else {
                //Different week, reset count and amount
                $this->history[$userId]["count"] = 1;
                $this->history[$userId]["value"] = $opValue;
                $this->history[$userId]["date"] = date("W",strtotime($opDate));
                //add week

                if ($opValue < TaxCalculator::MAX_OUT_TAX ) {
                    return 0.00;
                } else {
                    return $opValue - TaxCalculator::MAX_OUT_TAX; //Used up the new weeks limit, calculate the amount to apply tax to
                }
            }
        }
    }

}
