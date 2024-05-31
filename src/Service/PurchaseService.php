<?php

namespace App\Service;

use App\DTO\CalculatePriceRequest;
use App\DTO\PurchaseRequest;

class PurchaseService
{
    private PriceCalculatorService $priceCalculatorService;

    public function __construct(PriceCalculatorService $priceCalculatorService)
    {
        $this->priceCalculatorService = $priceCalculatorService;
    }

    public function processPurchase(PurchaseRequest $purchaseRequest): array
    {
        $calculatePriceRequest = new CalculatePriceRequest();
        $calculatePriceRequest->product = $purchaseRequest->product;
        $calculatePriceRequest->taxNumber = $purchaseRequest->taxNumber;
        $calculatePriceRequest->couponCode = $purchaseRequest->couponCode;

        $priceData = $this->priceCalculatorService->calculate($calculatePriceRequest);

        // @todo there isn't any payment processor class in base codes, but it's written there should be, so I do nothing related to payments for now

        return [
            'message' => 'Purchase completed successfully.',
            'amount' => $priceData,
            'processor' => $purchaseRequest->paymentProcessor,
        ];
    }
}
