<?php

namespace App\DTO;

use App\Validator\Constraints\ValidCoupon;
use App\Validator\Constraints\VatNumberFormat;
use App\Validator\Constraints\VisibleProduct;
use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceRequest
{
    // @todo translate all error messages
    #[Assert\NotBlank(message: 'The product is required.')]
    #[Assert\Type(type: 'integer', message: 'The product must be an integer.')]
    #[VisibleProduct()]
    public $product;

    // @todo use VatNumber instead of VatNumberFormat to validate also existence
    #[Assert\NotBlank(message: "The tax number is required.")]
    #[Assert\Type(type: 'string', message: 'The tax number must be a string.')]
    #[Assert\Length(
        min: 5,
        max: 20,
        minMessage: 'The tax number must be at least {{ limit }} characters long.',
        maxMessage: 'The tax number cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Regex(pattern: '/^[A-Za-z0-9]+$/', message: 'The tax number must contain only latin letters and numbers.')]
    #[VatNumberFormat]
    public $taxNumber;

    #[Assert\Type(type: 'string', message: 'The coupon code must be a string.')]
    #[Assert\Length(
        min: 4,
        max: 20,
        minMessage: 'The coupon code must be at least {{ limit }} characters long.',
        maxMessage: 'The coupon code cannot be longer than {{ limit }} characters.'
    )]
    #[ValidCoupon()]
    public $couponCode;
}
