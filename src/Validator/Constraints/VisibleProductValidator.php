<?php

namespace App\Validator\Constraints;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class VisibleProductValidator extends ConstraintValidator
{
    private ?EntityManagerInterface $entityManager = null;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof VisibleProduct) {
            throw new UnexpectedTypeException($constraint, VisibleProduct::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        $object = $this->context->getObject();
        if (!is_object($object)) {
            return;
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();

        $queryBuilder
            ->select('p', 'u')
            ->from(Product::class, 'p')
            ->leftJoin('p.user', 'u')
            ->where('p.id = :productId')
            ->andWhere('p.status = :productStatus')
            ->andWhere('u.status = :userStatus')
            ->andWhere('u.email_verified_at IS NOT NULL')
            ->setParameter('productId', $value)
            ->setParameter('productStatus', Product::STATUS_ACTIVE)
            ->setParameter('userStatus', User::STATUS_ACTIVE);

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->atPath('product')
                ->addViolation();
        }
    }
}
