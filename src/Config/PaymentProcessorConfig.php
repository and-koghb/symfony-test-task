<?php

namespace App\Config;

// @todo a better way could be storing of processors in db and having a status column for them,
// that admins can turn off necessary processor from admin panel immediately without asking programmers to disable in codes
// also by that way it'll be easy to list available payment methods with their proper names and logos
class PaymentProcessorConfig
{
    public const PAYPAL = 'paypal';
    public const STRIPE = 'stripe';
    public const SQUARE = 'square';

    const ALL_PROCESSORS = [
            self::PAYPAL,
            self::STRIPE,
            self::SQUARE,
        ];

    const AVAILAVBE_PROCESSORS = [
            self::PAYPAL,
            self::STRIPE,
        ];
}
