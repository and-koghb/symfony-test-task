<?php

namespace App\DTO;

use App\Config\PaymentProcessorConfig;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseRequest extends CalculatePriceRequest
{
    // @todo translate messages
    #[Assert\NotBlank(message: 'The payment processor is required.')]
    #[Assert\Type(type: 'string', message: 'The payment processor must be a string.')]
    #[Assert\Length(
        min: 2,
        max: 30,
        minMessage: 'The payment processor must be at least {{ limit }} characters long.',
        maxMessage: 'The payment processor cannot be longer than {{ limit }} characters.'
    )]
    #[Assert\Choice(choices: PaymentProcessorConfig::AVAILAVBE_PROCESSORS, message: 'The payment processor is invalid.')]
    public $paymentProcessor;
}
