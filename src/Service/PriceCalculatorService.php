<?php

namespace App\Service;

use App\DTO\CalculatePriceRequest;

class PriceCalculatorService
{
    private ProductService $productService;
    private DiscountService $discountService;
    private TaxService $taxService;

    public function __construct(
        ProductService $productService,
        DiscountService $discountService,
        TaxService $taxService
    ) {
        $this->productService = $productService;
        $this->discountService = $discountService;
        $this->taxService = $taxService;
    }

    public function calculate(CalculatePriceRequest $request): array
    {
        $product = $this->productService->getProductWithCurrency($request->product);
        $discountedPrice = $this->discountService->getDiscountedPrice($product, $request->couponCode);
        $taxPercent = $this->taxService->getTaxPercent($request->taxNumber);

        $price = $discountedPrice * (100 + $taxPercent) / 100;

        return [
            'price' => $price,
            'currency' => $product->getCurrency()->getCode(),
        ];
    }
}
