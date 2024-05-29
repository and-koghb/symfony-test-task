<?php

namespace App\Validator\Constraints;

use App\Entity\Coupon;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ValidCouponValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ValidCoupon) {
            throw new UnexpectedTypeException($constraint, ValidCoupon::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $object = $this->context->getObject();
        if (!is_object($object)) {
            return;
        }

        $productId = $object->product;

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('c')
            ->from(Coupon::class, 'c')
            ->leftJoin(Product::class, 'p', 'WITH', 'c.user = p.user')
            ->where('c.code = :couponCode')
            ->andWhere('c.status = :couponStatus')
            ->andWhere(
                $queryBuilder->expr()->orX(
                    $queryBuilder->expr()->eq('p.id', ':productId'),
                    $queryBuilder->expr()->isNull('c.user')
                )
            )
            ->setParameter('couponCode', $value)
            ->setParameter('couponStatus', Coupon::STATUS_VALID)
            ->setParameter('productId', $productId);

        $coupon = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$coupon) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ couponCode }}', $value)
                ->atPath('couponCode')
                ->addViolation();
        }
    }
}
