<?php

namespace App\Service;

use App\DTO\CalculatePriceRequest;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PriceCalculatorService
{
    private ValidatorInterface $validator;
    private ProductService $productService;
    private DiscountService $discountService;
    private TaxService $taxService;

    public function __construct(
        ValidatorInterface $validator,
        ProductService $productService,
        DiscountService $discountService,
        TaxService $taxService
    ) {
        $this->validator = $validator;
        $this->productService = $productService;
        $this->discountService = $discountService;
        $this->taxService = $taxService;
    }

    public function validate(CalculatePriceRequest $request): array
    {
        $errors = $this->validator->validate($request);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $errorMessages;
        }

        return [];
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
