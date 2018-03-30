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

    function validateOperation(array $operationDetails) : bool
    {
        $flag = true;

        if ($this->validateDate($operationDetails['date'])) {
        } else {
            $flag = false;
            throw new \Exception("Date invalid.");
        }

        if(is_string($operationDetails['userID'])) {
            if(!is_int((int)$operationDetails['userID'])){
                $flag = false;
                throw new \Exception("\nUser ID must be an int\n");
            }
        }
        if(is_string('userType')) {
        } else {
            $flag = false;
            throw new \Exception("\nUser Type must be a string\n");
        }

        if(is_string($operationDetails['operationType'])) {
        } else {
            $flag = false;
            throw new \Exception("\nOperation Type must be a string\n");
        }

        return $flag;
    }

    function validateDate($date, $format = 'Y-m-d')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
}