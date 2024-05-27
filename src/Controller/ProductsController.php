<?php

namespace App\Controller;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProductsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // @todo fix the function
    #[Route('/calculate-price', name: 'app_products', methods: 'POST')]
    public function index(): JsonResponse
    {
        $currencyRepository = $this->entityManager->getRepository(Currency::class);
        $queryBuilder = $currencyRepository->createQueryBuilder('c');
        $queryBuilder->select('COUNT(c.id)');
        $count = $queryBuilder->getQuery()->getSingleScalarResult();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductsController.php',
            'count' => $count,
        ]);
    }
}
