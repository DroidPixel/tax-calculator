<?php
/**
 * Created by PhpStorm.
 * User: paysera
 * Date: 18.30.3
 * Time: 13:36
 */

namespace Paysera\Tax;


use Money\Money;

class Tax
{
    private $percentage;
    private $minTax;
    private $maxTax;
    private $taxlessAmount;
    private $taxlessOperationsCount;

    /**
     * @return mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param mixed $percentage
     * @return Tax
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinTax()
    {
        return $this->minTax;
    }

    /**
     * @param mixed $minTax
     * @return Tax
     */
    public function setMinTax($minTax)
    {
        $this->minTax = $minTax;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxTax()
    {
        return $this->maxTax;
    }

    /**
     * @param mixed $maxTax
     * @return Tax
     */
    public function setMaxTax($maxTax)
    {
        $this->maxTax = $maxTax;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxlessAmount()
    {
        return $this->taxlessAmount;
    }

    /**
     * @param mixed $taxlessAmount
     * @return Tax
     */
    public function setTaxlessAmount($taxlessAmount)
    {
        $this->taxlessAmount = $taxlessAmount;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTaxlessOperationsCount()
    {
        return $this->taxlessOperationsCount;
    }

    /**
     * @param mixed $taxlessOperationsCount
     * @return Tax
     */
    public function setTaxlessOperationsCount($taxlessOperationsCount)
    {
        $this->taxlessOperationsCount = $taxlessOperationsCount;
        return $this;
    }
}