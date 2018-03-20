<?php
require_once 'Operation.php';
require_once 'TaxCalculator.php';
require_once 'CashOutSession.php';
require_once 'Converter.php';

$fileName = $argv[1];
$operationList = [];

if (($handle = fopen($fileName, "r")) !== false) {
    while (($data = fgetcsv($handle, 0 , ","))) {
        $operation = new Operation($data[0], $data[1], $data[2], $data[3], $data[4], $data[5]);
        $operationList[] = $operation;//Add
    }
}

//if operation == 'cash_out' && userType == 'natural'
//$cashOutSession->addToHistory($operation)
//koks userId, kokiaSuma
//if no entry for user -> add entry
//if entry exists -> add data to entry
//if entry exists but week has changed, override entry
//$cashOutSession = new CashOutSession();
//$cashOutSession->addToHistory($operation);

$calculator = new TaxCalculator();
foreach ($operationList as $operationItem) {
    echo $calculator->calculateTax($operationItem) . PHP_EOL;
}