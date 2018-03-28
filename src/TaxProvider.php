<?php

namespace Paysera;

class TaxProvider
{
    //cash_in
    const IN_TAX = 0.0003;
    const MAX_IN_TAX = 500;

    //cash_out
    const NATURAL_OUT_TAX = 0.003;
    const MAX_OUT_TAX = 1000.00;
    const MAX_CNT = 3;

    const LEGAL_OUT_TAX = 0.003;
    const MIN_OUT_TAX = 50;

    const BASE_CURRENCY = 'EUR';

    public function provideTax(Operation $operation): float
    {
        if ($operation->getType() == 'cash_in') {
            return TaxProvider::IN_TAX;
        } elseif ($operation->getType() == 'cash_out') {
            if ($operation->getUserType() == 'natural') {
                return TaxProvider::NATURAL_OUT_TAX;
            } elseif ($operation->getUserType() == 'legal') {
                return TaxProvider::LEGAL_OUT_TAX;
            }
        }
    }
}
