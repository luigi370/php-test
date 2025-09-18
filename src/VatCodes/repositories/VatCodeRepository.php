<?php

namespace src\VatCodes\repositories;

use src\core\DatabaseConnection;

class VatCodeRepository
{

    private DatabaseConnection $dbConnection;
    public function __construct()
    {
        $this->dbConnection = new DatabaseConnection();
    }

    /**
     * @throws \Exception
     */
    public function getVatCodes(): array
    {
        $sql = "SELECT * FROM test_db.vat_numbers;";
        return $this->dbConnection->executeQuery($sql)->fetchAll();
    }

    public function getVatCode(string $vatCode): array
    {
        $sql = "SELECT * FROM test_db.vat_numbers WHERE vat = '$vatCode';";
        return $this->dbConnection->executeQuery($sql)->fetchAll();
    }

    public function saveVatCode(string $vatCode): void
    {
        if ($this->getVatCode($vatCode)) {
             return;
         }
        $sql = "INSERT INTO vat_numbers (vat) VALUES ('$vatCode');";
        $this->dbConnection->executeQuery($sql);
    }
}