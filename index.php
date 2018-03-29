<?php

require __DIR__ . '/vendor/autoload.php';

use Money\Money;
use Paysera\CashOutSession;
use Paysera\InputParser;
use Paysera\MoneyConverter;
use Paysera\Operation;
use Paysera\OperationTaxLimitProvider;
use Paysera\TaxCalculator;
use Paysera\LimitChecker;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Currency;
use Money\Converter;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Paysera\TaxProvider;

$fileName = $argv[1];
$operationList = [];

$currencies = new ISOCurrencies();
$moneyParser = new DecimalMoneyParser($currencies);

$inputParserProvider = new InputParser();
$inputParserProvider->addParser('csv', $csvParser);
$inputParserProvider->addParser('csv', $csvParser);

//CSV PARSING
if (($handle = fopen($fileName, "r")) !== false) {
    while (($data = fgetcsv($handle))) {
        $operation = new Operation(
            new \DateTime($data[0]),
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
$baseCurrency = new Currency(TaxProvider::BASE_CURRENCY);

$cashOutSession = new CashOutSession(
    $baseCurrency,
    new Money(
        TaxProvider::MAX_OUT_TAX * 100,
        $baseCurrency
    )
);

//Initializing TaxCalculator obj
$calculator = new TaxCalculator();

$formatter = new DecimalMoneyFormatter($currencies);
$taxProvider = new TaxProvider();
$converter = new MoneyConverter(new Converter($currencies, $exchanger));

$limitChecker = new LimitChecker();
$limitProvider = new OperationTaxLimitProvider($baseCurrency);

foreach ($operationList as $operationItem) {
    $amount = $operationItem->getMoney();
    $percentage = $taxProvider->provideTax($operationItem);

    if (!$amount->getCurrency()->equals($baseCurrency)) {
        $amount = $converter->convert($amount, $baseCurrency);
    }

    if ($operationItem->getType() == "cash_out"
        && $operationItem->getUserType() == "natural"
    ) {
        $amount = $cashOutSession->addToHistory(
            $operationItem->getUserId(),
            $amount,
            $operationItem->getDate()
        );
    }

    if ($operationItem->getMoney()->getCurrency()->getCode() === 'EUR') {
        $roundMode = Money::ROUND_UP;
    } else {
        $roundMode = Money::ROUND_HALF_UP;
    }

    $calculatedAmount = $calculator->calculateTax($amount, $percentage, $roundMode);

    //Sets limits accordingly
    $taxLimit = $limitProvider->getTaxLimit($operationItem);
    $finalAmount = $limitChecker->checkAgainstLimit($calculatedAmount, $taxLimit);

    if (!$calculatedAmount->getCurrency()->equals($operationItem->getCurrency())) {
        $finalAmount = $converter->convert($finalAmount, $operationItem->getCurrency());
    }

    echo $formatter->format($finalAmount) . PHP_EOL;
}
