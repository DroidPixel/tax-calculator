<?php

namespace Paysera;

class Converter
{
    CONST EUR = "EUR";
    const CURRENCY = [
        "EUR"=>"EUR",
        "USD"=>"USD",
        "JPY"=>"JPY"
    ];

    private $rates;

    public function __construct()
    {
        $this->rates = [
            "USD"=> 1.1497,
            "JPY" => 129.53
        ];
    }

    public function convert($sum, $from, $to = Converter::CURRENCY["EUR"])
    {
        if ($from == "EUR") {
            return $sum * $this->rates[$to];
        } elseif (isset($this->rates[$from])) {
            //Assuming $to can only be EUR
            return $sum / $this->rates[$from];
        }
    }

    public function convertRound(
        $sum,
        $to,
        $from = Converter::CURRENCY["EUR"],
        $needsConversion = false
    ) {
        if ($needsConversion) {
            $sum = $this->convert($sum, $from, $to);
        }

        if (in_array($to, [Converter::CURRENCY["USD"], Converter::CURRENCY["EUR"]])) {
            return round($sum, 2, PHP_ROUND_HALF_UP);
        } elseif ($to == Converter::CURRENCY["JPY"]) {
            return round($sum, 0, PHP_ROUND_HALF_UP);
        }
    }
}
