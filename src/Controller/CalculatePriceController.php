<?php

namespace App\Controller;

use App\DTO\CalculatePriceRequest;
use App\Form\CalculatePriceType;
use App\Service\PriceCalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class CalculatePriceController extends AbstractController
{
    private PriceCalculatorService $priceCalculatorService;

    public function __construct(PriceCalculatorService $priceCalculatorService)
    {
        $this->priceCalculatorService = $priceCalculatorService;
    }

    #[Route('/calculate-price', name: 'app_products', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $calculatePriceRequest = new CalculatePriceRequest();
        $form = $this->createForm(CalculatePriceType::class, $calculatePriceRequest);
        $form->submit($data);

        $errors = $this->priceCalculatorService->validate($calculatePriceRequest);
        if (!empty($errors)) {
            return $this->json(['errors' => $errors], 400);
        }

        $result = $this->priceCalculatorService->calculate($calculatePriceRequest);

        return $this->json($result);
    }
}
