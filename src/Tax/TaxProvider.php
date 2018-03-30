<?php

namespace Paysera\Tax;

use Paysera\Operations\Operation;

class TaxProvider
{
    private $percentager;

    public function __construct(TaxPercentage $percentager)
    {
        $this->percentager = $percentager;
    }

    public function provideTax(Operation $operation): float
    {
        if ($operation->getType() == 'cash_in') {
            return $this->percentager->getInputTax();
        } elseif ($operation->getType() == 'cash_out') {
            if ($operation->getUserType() == 'natural') {
                return $this->percentager->getNaturalOutputTax();
            } elseif ($operation->getUserType() == 'legal') {
                return $this->percentager->getLegalOutputTax();
            }
        }
    }
}
