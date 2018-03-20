<?php

class Converter
{
    private $rates;

    public function __construct()
    {
        $this->rates = [
            "USD"=> 1.1497,
            "JPY" => 129.53
        ];
    }

    public function convert($sum, $from, $to = "EUR")
    {
        if ($from == "EUR") {
            return $sum * $this->rates[$to];
        } elseif (isset($this->rates[$from])) {
            //Assuming $to can only be EUR
            return $sum / $this->rates[$from];
        }
    }

    public function convertRound($sum, $to, $from = "EUR", $needsConversion = false)
    {
        if ($needsConversion) {
            $sum = $this->convert($sum, $from, $to);
        }

        if (in_array($to, ["USD", "EUR"])) {
            return round($sum, 2, PHP_ROUND_HALF_UP);
        } elseif ($to == "JPY") {
            return round($sum, 0, PHP_ROUND_HALF_UP);
        }
    }

}