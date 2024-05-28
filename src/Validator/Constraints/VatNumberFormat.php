<?php

namespace App\Validator\Constraints;

use Ibericode\Vat\Bundle\Validator\Constraints\VatNumber;
use Ibericode\Vat\Bundle\Validator\Constraints\VatNumberValidator;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class VatNumberFormat extends VatNumber
{
    public bool $checkExistence = false;

    public function validatedBy(): string
    {
        return VatNumberValidator::class;
    }
}
