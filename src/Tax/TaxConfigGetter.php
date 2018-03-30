<?php

namespace Paysera\Tax;

class TaxConfigGetter
{
    const FILENAME = 'config.ini';

    /**
     * TaxConfigGetter constructor.
     * @throws \Exception
     */
    public function getConfig()
    {
        $iniArray = parse_ini_file($this::FILENAME, false, INI_SCANNER_TYPED);

        if ($iniArray) {
            return $iniArray;
        } else {
            throw new \Exception("\nConfig file was not found!");
        }
    }
}