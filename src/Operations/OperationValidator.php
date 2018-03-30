<?php

namespace Paysera\Operations;


use DateTime;

class OperationValidator
{
    /**
     * @param array $operationDetails
     * @return bool
     * @throws \Exception
     */
    public function validateOperation(array $operationDetails) : bool
    {
        if (!$this->validateDate($operationDetails['date'])) {
            throw new \Exception("Date invalid.");
        }

        if(!is_numeric($operationDetails['userID'])){
            throw new \Exception("User ID must be an int\n");
        }

        //@TODO check if in available types
        if(!is_string($operationDetails['userType'])) {
            throw new \Exception("User Type must be a string\n");
        }

        if(!is_string($operationDetails['operationType'])) {
            throw new \Exception("Operation Type must be a string\n");
        }

        return true;
    }

    public function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}