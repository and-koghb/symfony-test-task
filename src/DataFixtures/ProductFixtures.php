<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $qb = $manager->createQueryBuilder();
        $query = $qb->select('COUNT(p.id)')
            ->from(Product::class, 'p')
            ->getQuery();
        $count = $query->getSingleScalarResult();
        if ($count > 0) {
            return;
        }

        $products = [
            [
                'name' => 'iPhone',
                'price' => 100,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'john@example.com',
            ], [
                'name' => 'Headphones',
                'price' => 20,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'jane@example.com',
            ], [
                'name' => 'Case',
                'price' => 10,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'alice@example.com',
            ], [
                'name' => 'Charger',
                'price' => 15,
                'currency' => 'EUR',
                'status' => Product::STATUS_INACTIVE,
                'user_email' => 'john@example.com',
            ], [
                'name' => 'Screen Protector',
                'price' => 5,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'john@example.com',
            ], [
                'name' => 'Wireless Mouse',
                'price' => 25,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'jane@example.com',
            ], [
                'name' => 'Keyboard',
                'price' => 30,
                'currency' => 'EUR',
                'status' => Product::STATUS_INACTIVE,
                'user_email' => 'alice@example.com',
            ], [
                'name' => 'USB Cable',
                'price' => 8,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'michael@example.com',
            ], [
                'name' => 'External Hard Drive',
                'price' => 50,
                'currency' => 'EUR',
                'status' => Product::STATUS_ACTIVE,
                'user_email' => 'john@example.com',
            ], [
                'name' => 'Smart Watch',
                'price' => 150,
                'currency' => 'EUR',
                'status' => Product::STATUS_INACTIVE,
                'user_email' => 'jane@example.com',
            ],
        ];

        foreach ($products as $productData) {
            $user = $manager->getRepository(User::class)->findOneBy(['email' => $productData['user_email']]);
            if ($user) {
                $product = new Product();
                $product->setName($productData['name']);
                $product->setPrice($productData['price']);
                $currency = $manager->getRepository(Currency::class)->findOneBy(['code' => $productData['currency']]);
                if ($currency) {
                    $product->setCurrency($currency);
                }
                $product->setUser($user);
                $product->setStatus($productData['status']);
                $product->setCreatedAt(new \DateTime());
                $product->setUpdatedAt(new \DateTime());
                $manager->persist($product);
            }

        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test-task'];
    }
}
