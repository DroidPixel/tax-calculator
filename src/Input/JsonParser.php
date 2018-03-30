<?php

namespace Paysera\Input;

class JsonParser implements InputParserInterface
{
    /**
     * @param $fileName
     * @return array
     * @throws \Exception
     */

    public function parseFromFile($fileName)
    {
        if (file_get_contents($fileName)) {
            $contents = file_get_contents($fileName);
            if (json_decode($contents) !== null) {

                $jsonData = $this->formatInputData(json_decode($contents, true));

                return $jsonData;
            }
        } else {
            throw new \Exception("\nInput file was either not found or couldn't be opened. \n");
        }
    }

    private function formatInputData($data): array
    {
        foreach($data as $dataItem) {
            $jsonData[] = [
                'date' => $dataItem['operation_date'],
                'userID' => $dataItem['user_id'],
                'userType' => $dataItem['user_type'],
                'operationType' => $dataItem['operation_type'],
                'amount' => $dataItem['amount'],
                'currency' => $dataItem['currency']
            ];
        }

        return $jsonData;
    }
}