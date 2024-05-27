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

        $currencyData = [
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

        foreach ($currencyData as $currencyDetails) {
            $currency = new Currency();
            $currency->setName($currencyDetails['name']);
            $currency->setCode($currencyDetails['code']);
            $currency->setSubUnit($currencyDetails['subUnit']);
            $currency->setDecimals($currencyDetails['decimals']);
            $currency->setSymbol($currencyDetails['symbol']);
            $currency->setType($currencyDetails['type']);
            $currency->setStatus($currencyDetails['status']);
            $manager->persist($currency);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test-task'];
    }
}
