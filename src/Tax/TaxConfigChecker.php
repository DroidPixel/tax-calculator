<?php

namespace Paysera\Tax;


class TaxConfigChecker
{
    public function configCheck($configArray)
    {
        if (sizeof($configArray) == 8) {
            if(!key_exists('BaseCurrency', $configArray)
                || !is_string($configArray['BaseCurrency'])
                || sizeof($configArray['BaseCurrency']) > 3
            ) {
                return false;
            }

            if(!key_exists('InputTax', $configArray) || !is_double($configArray['InputTax'])) {
                return false;
            }

            if(!key_exists('MaximumInputTax', $configArray) || (!is_int($configArray['MaximumInputTax']))) {
                if(!is_double($configArray['MaximumInputTax'])) {
                    return false;
                }
            }

            if(!key_exists('NaturalOutputTax', $configArray) || !is_double($configArray['NaturalOutputTax'])) {
                return false;
            }

            if(!key_exists('MaximumOutputTax', $configArray) || !is_double($configArray['MaximumOutputTax'])) {
                return false;
            }

            if(!key_exists('MaximumCount', $configArray) || !is_int($configArray['MaximumCount'])) {
                return false;
            }

            if(!key_exists('LegalOutputTax', $configArray) || !is_double($configArray['LegalOutputTax'])) {
                return false;
            }

            if(!key_exists('MinimumOutputTax', $configArray) || !is_int($configArray['MinimumOutputTax'])) {
                if(!is_double($configArray['MinimumOutputTax'])) {
                    return false;
                }
            }
        } else {
            return false;
        }

        return true;
    }
}