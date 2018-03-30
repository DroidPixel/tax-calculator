<?php

require __DIR__ . '/vendor/autoload.php';

use Money\Money;
use Paysera\CashOutSession;
use Paysera\Input\CsvParser;
use Paysera\Input\InputParserProvider;
use Paysera\Input\JsonParser;
use Paysera\MoneyConverter;
use Paysera\Operations\OperationManager;
use Paysera\Operations\OperationTaxLimitProvider;
use Paysera\Tax\TaxCalculator;
use Paysera\LimitChecker;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Currency;
use Money\Converter;
use Money\Exchange\FixedExchange;
use Money\Exchange\ReversedCurrenciesExchange;
use Paysera\Tax\TaxPercentage;
use Paysera\Tax\TaxProvider;

$fileName = $argv[1];
$extension = $argv[2];
$operationList = [];

$currencies = new ISOCurrencies();
$calculator = new TaxCalculator();
$percentager = new TaxPercentage();

$formatter = new DecimalMoneyFormatter($currencies);
$taxProvider = new TaxProvider($percentager);

$exchanger = new ReversedCurrenciesExchange(new FixedExchange([
    'EUR' => [
        'USD' => 1.1497,
        'JPY' => 129.53
    ]
]));
$converter = new MoneyConverter(new Converter($currencies, $exchanger));

$baseCurrency = new Currency($percentager->getBaseCurrency());
$limitChecker = new LimitChecker();
$limitProvider = new OperationTaxLimitProvider($baseCurrency, $percentager);
$inputParserProvider = (new InputParserProvider())
    ->addParser('csv', new CsvParser())
    ->addParser('json', new JsonParser());

$cashOutSession = new CashOutSession(
    $baseCurrency,
    new Money(
        $percentager->getMaximumOutputTax() * 100,
        $baseCurrency
    ),
    $percentager->getMaximumCount()
);

$operationManager = new OperationManager(new DecimalMoneyParser($currencies));

try {
    $inputParser = $inputParserProvider->getParserByKey($extension);
    $parsedInputArray = $inputParser->parseFromFile($fileName);
} catch(Exception $e) {
    echo $e->getMessage();
    exit();
}

foreach ($parsedInputArray as $parsedInput) {
    try {
        $operationList[] = $operationManager->createOperation($parsedInput);
    } catch(Exception $e) {
        echo $e->getMessage();
        exit();
    }
}

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
