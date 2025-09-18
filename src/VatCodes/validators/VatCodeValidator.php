<?php

namespace src\VatCodes\validators;

use InvalidArgumentException;

class VatCodeValidator
{
    private $regex = '/^IT\d{11}$/';

    public function vatCodeValidation($input): bool {
        $cleanedInput = trim($input);

        if (empty($cleanedInput)) {
            throw new InvalidArgumentException('VAT code cannot be empty');
        }

        if (!preg_match('/^IT/', $cleanedInput)) {
            throw new InvalidArgumentException('VAT code must start with "IT"');
        }

        if (strlen($cleanedInput) !== 13) {
            throw new InvalidArgumentException('VAT code must be exactly 13 characters long (IT + 11 digits)');
        }

        $digitsPart = substr($cleanedInput, 2);
        if (!preg_match('/^\d{11}$/', $digitsPart)) {
            throw new InvalidArgumentException('VAT code must contain exactly 11 digits after "IT"');
        }

        return true;
    }
}