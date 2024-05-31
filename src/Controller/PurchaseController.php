<?php

namespace App\Controller;

use App\DTO\PurchaseRequest;
use App\Form\PurchaseType;
use App\Service\PurchaseService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PurchaseController extends BaseController
{
    private PurchaseService $purchaseService;

    public function __construct(ValidatorInterface $validator, PurchaseService $purchaseService)
    {
        parent::__construct($validator);
        $this->purchaseService = $purchaseService;
    }

    #[Route('/purchase', name: 'app_purchase', methods: 'POST')]
    public function make(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $purchaseRequest = new PurchaseRequest();
        $form = $this->createForm(PurchaseType::class, $purchaseRequest);
        $form->submit($data);

        $validationResponse = $this->validateRequest($purchaseRequest);
        if ($validationResponse) {
            return $validationResponse;
        }

        $result = $this->purchaseService->processPurchase($purchaseRequest);

        return $this->json($result);
    }
}
