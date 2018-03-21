<?php

require __DIR__ . '/vendor/autoload.php';

use Paysera\CashOutSession;
use Paysera\Converter;
use Paysera\Operation;
use Paysera\TaxCalculator;

$fileName = $argv[1];
$operationList = [];

if (($handle = fopen($fileName, "r")) !== false) {
    while (($data = fgetcsv($handle, 0 , ","))) {
        $operation = new Operation(
            $data[0],
            $data[1],
            $data[2],
            $data[3],
            $data[4],
            $data[5]
        );
        $operationList[] = $operation;//Add
    }
}

/*if operation == 'cash_out' && userType == 'natural'
$cashOutSession->addToHistory($operation)
koks userId, kokiaSuma
if no entry for user -> add entry
if entry exists -> add data to entry
if entry exists but week has changed, override entry
$cashOutSession = new CashOutSession();
$cashOutSession->addToHistory($operation);
*/

$calculator = new TaxCalculator(new CashOutSession(), new Converter());
foreach ($operationList as $operationItem) {
    echo $calculator->calculateTax($operationItem) . PHP_EOL;
}