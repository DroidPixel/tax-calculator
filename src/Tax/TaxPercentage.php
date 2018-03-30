<?php

namespace Paysera\Tax;

class TaxPercentage
{
    //cash_in
    private $inputTax;
    private $maximumInputTax;

    //cash_out
    private $naturalOutputTax;
    private $maximumOutputTax;
    private $maximumCount;

    private $legalOutputTax;
    private $minimumOutputTax;

    private $baseCurrency;

    public function __construct()
    {
        $configGetter = new TaxConfigGetter();
        $configArray = $configGetter->getConfig();
        $configChecker = new TaxConfigChecker();

        if ($configChecker->configCheck($configArray)) {
            $this->inputTax = $configArray['InputTax'];
            $this->maximumInputTax = $configArray['MaximumInputTax'];
            $this->naturalOutputTax = $configArray['NaturalOutputTax'];
            $this->maximumOutputTax = $configArray['MaximumOutputTax'];
            $this->maximumCount = $configArray['MaximumCount'];
            $this->legalOutputTax = $configArray['LegalOutputTax'];
            $this->minimumOutputTax = $configArray['MinimumOutputTax'];
            $this->baseCurrency = $configArray['BaseCurrency'];
        } else {
            throw new \Exception("Config info invalid!");
        }
    }

    /**
     * @return mixed
     */
    public function getInputTax()
    {
        return $this->inputTax;
    }

    /**
     * @return mixed
     */
    public function getMaximumInputTax()
    {
        return $this->maximumInputTax;
    }

    /**
     * @return mixed
     */
    public function getNaturalOutputTax()
    {
        return $this->naturalOutputTax;
    }

    /**
     * @return mixed
     */
    public function getMaximumOutputTax()
    {
        return $this->maximumOutputTax;
    }

    /**
     * @return mixed
     */
    public function getMaximumCount()
    {
        return $this->maximumCount;
    }

    /**
     * @return mixed
     */
    public function getLegalOutputTax()
    {
        return $this->legalOutputTax;
    }

    /**
     * @return mixed
     */
    public function getMinimumOutputTax()
    {
        return $this->minimumOutputTax;
    }

    /**
     * @return mixed
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }
}