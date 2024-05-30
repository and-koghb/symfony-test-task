<?php

namespace App\Service;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class DiscountService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getDiscountedPrice(Product $product, ?string $couponCode): float
    {
        $discountPercent = $this->getDiscountPercent($product, $couponCode);
        return $product->getPrice() * (100 - $discountPercent) / 100;
    }

    // @todo make public if the project needs to use it from somewhere else
    private function getDiscountPercent(Product $product, ?string $couponCode): float
    {
        if (!$couponCode) {
            return 0;
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $coupon = $queryBuilder
            ->select('c.percent')
            ->from(Coupon::class, 'c')
            ->where('c.code = :couponCode')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('c.user', ':userId'),
                    $queryBuilder->expr()->isNull('c.user')
                )
            )
            ->setParameter('couponCode', $couponCode)
            ->setParameter('userId', $product->getUser()->getId())
            ->getQuery()
            ->getOneOrNullResult();

        return $coupon['percent'] ?? 0;
    }
}
