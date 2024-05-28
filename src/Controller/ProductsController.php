<?php

namespace App\Controller;

use App\DTO\CalculatePriceRequest;
use App\Entity\Currency;
use App\Entity\Product;
use App\Form\CalculatePriceType;
use Doctrine\ORM\EntityManagerInterface;
use Ibericode\Vat\Countries;
use Ibericode\Vat\Geolocator;
use Ibericode\Vat\Rates;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProductsController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Rates $rates;

    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        Rates $rates,
        Countries $countries,
        Geolocator $geolocator,
    ) {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->rates = $rates;
    }

    // @todo continue on the function
    #[Route('/calculate-price', name: 'app_products', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $calculatePriceRequest = new CalculatePriceRequest($this->entityManager);
        $form = $this->createForm(CalculatePriceType::class, $calculatePriceRequest);
        $form->submit($data);

        $errors = $this->validator->validate($calculatePriceRequest);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorMessages], 400);
        }

        $productId = $data['product'];
        $product = $this->entityManager
            ->createQueryBuilder()
            ->select('p', 'c')
            ->from(Product::class, 'p')
            ->leftJoin('p.currency', 'c')
            ->where('p.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getOneOrNullResult();

        $taxNumber = $data['taxNumber'];
        $countryCode = substr($taxNumber, 0, 2);
        $tax = $this->rates->getRateForCountry($countryCode);

        $couponCode = $data['couponCode'];

        return $this->json([
            'productPrice' => $product->getPrice(),
            'currency' => $product->getCurrency()->getCode(),
            'taxNumber' => $taxNumber,
            'tax' => $tax,
            'couponCode' => $couponCode,
        ]);
    }
}
