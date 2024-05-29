<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CurrencyFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $qb = $manager->createQueryBuilder();
        $query = $qb->select('COUNT(c.id)')
            ->from(Currency::class, 'c')
            ->getQuery();
        $count = $query->getSingleScalarResult();
        if ($count > 0) {
            return;
        }

        $currenciesData = [
            [
                'name' => 'US Dollar',
                'code' => 'USD',
                'subUnit' => 'Cent',
                'decimals' => 2,
                'symbol' => '$',
                'type' => Currency::TYPE_NORMAL,
                'status' => Currency::STATUS_ACTIVE,
            ], [
                'name' => 'Euro',
                'code' => 'EUR',
                'subUnit' => 'Cent',
                'decimals' => 2,
                'symbol' => '€',
                'type' => Currency::TYPE_NORMAL,
                'status' => Currency::STATUS_ACTIVE,
            ], [
                'name' => 'Fast Token',
                'code' => 'FTN',
                'subUnit' => null,
                'decimals' => 8,
                'symbol' => null,
                'type' => Currency::TYPE_CRYPTO,
                'status' => Currency::STATUS_ACTIVE,
            ],
        ];

        foreach ($currenciesData as $currencyData) {
            $currency = new Currency();
            $currency->setName($currencyData['name']);
            $currency->setCode($currencyData['code']);
            $currency->setSubUnit($currencyData['subUnit']);
            $currency->setDecimals($currencyData['decimals']);
            $currency->setSymbol($currencyData['symbol']);
            $currency->setType($currencyData['type']);
            $currency->setStatus($currencyData['status']);
            $manager->persist($currency);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test-task'];
    }
}
