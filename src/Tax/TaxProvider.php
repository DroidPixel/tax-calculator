<?php

namespace Paysera\Tax;

use Money\Currency;
use Money\Money;
use Paysera\Operations\Operation;

class TaxProvider
{
    private $taxConfig;

    public function __construct(array $taxConfig)
    {
        $this->taxConfig = $taxConfig;
    }

    public function provideTax(Operation $operation): Tax
    {
        foreach ($this->taxConfig as $item) {
            if ($item['userType'] === $operation->getUserType() &&
                $item['operationType'] === $operation->getType()
            ) {
                return $this->buildTaxFromConfig($item);
            }

            if ($item['operationType'] === $operation->getType()) {
                return $this->buildTaxFromConfig($item);
            }

            //throw new ConfigNotFoundException();
        }
    }

    private function buildTaxFromConfig($config)
    {
        $tax = new Tax();
        $tax->setPercentage($config['percentage'] ?? null);
        $tax->setTaxlessOperationsCount($config['taxlessOperationsCount'] ?? null);
        $tax->setMinTax(isset($config['minTax']) ? new Money($config['minTax'], new Currency('EUR')) : null);
        $tax->setMaxTax(isset($config['maxTax']) ? new Money($config['maxTax'], new Currency('EUR')) : null);
        $tax->setTaxlessAmount(isset($config['taxlessAmount']) ? new Money($config['taxlessAmount'], new Currency('EUR')) : null);

        return $tax;
    }
}
