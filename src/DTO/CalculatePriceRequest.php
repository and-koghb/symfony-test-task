<?php

namespace App\DTO;

use App\Entity\Product;
use App\Entity\User;
use App\Validator\Constraints\VatNumberFormat;
use Doctrine\ORM\EntityManagerInterface;
use Ibericode\Vat\Bundle\Validator\Constraints\VatNumber;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CalculatePriceRequest
{
    private static ?EntityManagerInterface $entityManager = null;

    public function __construct(EntityManagerInterface $entityManager)
    {
        self::$entityManager = $entityManager;
    }

    #[Assert\NotBlank(message: 'The product is required.')]
    #[Assert\Type(type: 'integer', message: 'The product must be an integer.')]
    #[Assert\Callback([CalculatePriceRequest::class, 'productExists'])]
    public $product;

    // @todo translate error messages
    // @todo use VatNumber instead of VatNumberFormat to validate also existence
    #[Assert\NotBlank(message: "The tax number should not be blank.")]
    #[Assert\Type(type: 'string', message: 'The tax number must be a string.')]
    #[Assert\Length(min: 5, max: 20, minMessage: 'The tax number must be at least {{ limit }} characters long.', maxMessage: 'The tax number cannot be longer than {{ limit }} characters.')]
    #[Assert\Regex(pattern: '/^[A-Za-z0-9]+$/', message: 'The tax number must contain only letters and numbers.')]
    #[VatNumberFormat]
    public $taxNumber;

    #[Assert\NotBlank(message: 'The coupon code is required.')]
    #[Assert\Type(type: 'string', message: 'The coupon code must be a string.')]
    public $couponCode;

    public static function productExists($productId, ExecutionContextInterface $context, $payload)
    {
        $queryBuilder = self::$entityManager->createQueryBuilder();

        $queryBuilder
            ->select('p', 'u')
            ->from(Product::class, 'p')
            ->leftJoin('p.user', 'u')
            ->where('p.id = :productId')
            ->andWhere('p.status = :productStatus')
            ->andWhere('u.status = :userStatus')
            ->andWhere('u.email_verified_at IS NOT NULL')
            ->setParameter('productId', $productId)
            ->setParameter('productStatus', Product::STATUS_ACTIVE)
            ->setParameter('userStatus', User::STATUS_ACTIVE);

        $result = $queryBuilder->getQuery()->getOneOrNullResult();

        if (!$result) {
            // @todo translate the error message
            $context->buildViolation('The product does not exist.')
                ->atPath('product')
                ->addViolation();
        }
    }
}
