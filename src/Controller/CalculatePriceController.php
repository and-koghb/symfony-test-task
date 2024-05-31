<?php

namespace App\Controller;

use App\DTO\CalculatePriceRequest;
use App\Form\CalculatePriceType;
use App\Service\PriceCalculatorService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculatePriceController extends BaseController
{
    private PriceCalculatorService $priceCalculatorService;

    public function __construct(ValidatorInterface $validator, PriceCalculatorService $priceCalculatorService)
    {
        parent::__construct($validator);
        $this->priceCalculatorService = $priceCalculatorService;
    }

    #[Route('/calculate-price', name: 'app_products', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $calculatePriceRequest = new CalculatePriceRequest();
        $form = $this->createForm(CalculatePriceType::class, $calculatePriceRequest);
        $form->submit($data);

        $validationResponse = $this->validateRequest($calculatePriceRequest);
        if ($validationResponse) {
            return $validationResponse;
        }

        $result = $this->priceCalculatorService->calculate($calculatePriceRequest);

        return $this->json($result);
    }
}
