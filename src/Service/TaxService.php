<?php

namespace App\Service;

use Ibericode\Vat\Rates;

class TaxService
{
    private Rates $rates;

    public function __construct(Rates $rates)
    {
        $this->rates = $rates;
    }

    public function getTaxPercent(string $taxNumber): float
    {
        $countryCode = substr($taxNumber, 0, 2);
        return $this->rates->getRateForCountry($countryCode);
    }
}
