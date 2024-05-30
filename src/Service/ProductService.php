<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProductWithCurrency(int $productId): ?Product
    {
        return $this->entityManager
            ->createQueryBuilder()
            ->select('p', 'c')
            ->from(Product::class, 'p')
            ->leftJoin('p.currency', 'c')
            ->where('p.id = :productId')
            ->setParameter('productId', $productId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
