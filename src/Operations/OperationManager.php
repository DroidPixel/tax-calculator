<?php

namespace Paysera\Operations;

use Money\MoneyParser;

class OperationManager
{
    private $moneyParser;

    public function __construct(MoneyParser $moneyParser)
    {
        $this->moneyParser = $moneyParser;
    }

    /**
     * @param array $operationDetails
     * @return Operation
     * @throws \Exception
     */

    public function createOperation(array $operationDetails) : Operation
    {
        $operation = new Operation();
        $operationValidator = new OperationValidator();

        if ($operationValidator->validateOperation($operationDetails)) {
            $operation->setDate(new \DateTime($operationDetails['date']));
            $operation->setUserId($operationDetails['userID']);
            $operation->setUserType($operationDetails['userType']);
            $operation->setType($operationDetails['operationType']);
            $operation->setMoney(
                $this->moneyParser->parse(
                    $operationDetails['amount'],
                    $operationDetails['currency']
                )
            );
            return $operation;
        }
    }
}