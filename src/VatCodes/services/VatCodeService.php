<?php

namespace src\VatCodes\services;

use src\VatCodes\repositories\VatCodeRepository;
use src\VatCodes\validators\VatCodeValidator;

class VatCodeService
{
    private VatCodeRepository $repository;
    private VatCodeValidator $vatCodeValidator;
    public function __construct()
    {
        $this->repository = new VatCodeRepository();
        $this->vatCodeValidator = new VatCodeValidator();
    }

    public function processVatCode($vatCode): bool
    {
        if ($this->vatCodeValidator->vatCodeValidation($vatCode)) {
             $this->repository->saveVatCode($vatCode);
             return true;
         }

        return false;
    }

    /**
     * @throws \Exception
     */
    public function getVatCodes(): array
    {
        return $this->repository->getVatCodes();
    }
}