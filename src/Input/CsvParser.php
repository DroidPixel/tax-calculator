<?php

namespace Paysera\Input;

class CsvParser implements InputParserInterface
{
    /**
     * @param $fileName
     * @return array
     * @throws \Exception
     */
    public function parseFromFile($fileName): array
    {
        if (($handle = fopen($fileName, "r")) !== false) {
            $csvData = [];

            while (($data = fgetcsv($handle))) {
                $csvData[] = $this->formatInputData($data);
            }

            return $csvData;
        }

        throw new \Exception("\nInput file was either not found or couldn't be opened. \n");
    }

    private function formatInputData($data): array
    {
        $csvData = [
            'date' => $data[0],
            'userID' => $data[1],
            'userType' => $data[2],
            'operationType' => $data[3],
            'amount' => $data[4],
            'currency' => $data[5]
        ];
        return $csvData;
    }
}