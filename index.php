<?php

require __DIR__ . '/vendor/autoload.php';

use Paysera\CashOutSession;
use Paysera\MoneyConverter;
use Paysera\Operation;
use Paysera\TaxCalculator;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Currency;
use Money\Converter;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;

$fileName = $argv[1];
$operationList = [];

$currencies = new ISOCurrencies();
$moneyParser = new DecimalMoneyParser($currencies);

//CSV PARSING
if (($handle = fopen($fileName, "r")) !== false) {

    while (($data = fgetcsv($handle))) {
        $operation = new Operation(
            $data[0],
            $data[1],
            $data[2],
            $data[3],
            $moneyParser->parse($data[4], $data[5])
        );
        $operationList[] = $operation;
    }
}

//Setting exchanger
$exchanger = new ReversedCurrenciesExchange(new FixedExchange([
    'EUR' => [
        'USD' => 1.1497,
        'JPY' => 129.53
    ]
]));

//Setting base currency to do limit calculations
$baseCurrency = new Currency("EUR");

//Initializing TaxCalculator obj
$calculator = new TaxCalculator(
    new CashOutSession($baseCurrency),
    new MoneyConverter(
        new Converter(
            new ISOCurrencies(),
            $exchanger
        )
    )
);
$formatter = new DecimalMoneyFormatter($currencies);

foreach ($operationList as $operationItem) {
    echo $formatter->format($calculator->calculateTax($operationItem)) . PHP_EOL;
}