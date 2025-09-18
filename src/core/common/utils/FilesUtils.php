<?php

namespace src\core\common\utils;

use Exception;

class FilesUtils
{
    /**
     * @throws Exception
     */
    public function loadVatNumbersFromCSV(string $filePath): array
    {
        $vatNumbers = array();

        if (!file_exists($filePath)) {
            throw new Exception("File not found: " . $filePath);
        }

        if (($handle = fopen($filePath, "r")) !== FALSE) {
            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                if (isset($data[1]) && !empty($data[1])) {
                    $vatNumbers[] = trim($data[1]);
                }
            }
            fclose($handle);
        }

        return $vatNumbers;
    }

}