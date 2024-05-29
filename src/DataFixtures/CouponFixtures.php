<?php

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $couponsData = [
            [
                'code' => 'STTJ25',
                'percent' => 25,
                'status' => Coupon::STATUS_VALID,
                'user_email' => 'john@example.com',
            ], [
                'code' => 'STTJ50',
                'percent' => 50,
                'status' => Coupon::STATUS_VALID,
                'user_email' => 'jane@example.com',
            ], [
                'code' => 'STTCOMMON30',
                'percent' => 30,
                'status' => Coupon::STATUS_INVALID,
            ], [
                'code' => 'STTA10',
                'percent' => 10,
                'status' => Coupon::STATUS_VALID,
                'user_email' => 'alice@example.com',
            ], [
                'code' => 'STTJ10',
                'percent' => 10,
                'status' => Coupon::STATUS_INVALID,
                'user_email' => 'john@example.com',
            ], [
                'code' => 'STTM15',
                'percent' => 15,
                'status' => Coupon::STATUS_INVALID,
                'user_email' => 'michael@example.com',
            ], [
                'code' => 'STTJ20',
                'percent' => 20,
                'status' => Coupon::STATUS_VALID,
                'user_email' => 'john@example.com',
            ], [
                'code' => 'STTCOMMON25',
                'percent' => 25,
                'status' => Coupon::STATUS_VALID,
            ], [
                'code' => 'STTJ30',
                'percent' => 30,
                'status' => Coupon::STATUS_INVALID,
                'user_email' => 'jane@example.com',
            ],
        ];

        foreach ($couponsData as $couponData) {
            $coupon = new Coupon();
            if (!empty($couponData['user_email'])) {
                $user = $manager->getRepository(User::class)->findOneBy(['email' => $couponData['user_email']]);
                if ($user instanceof User) {
                    $coupon->setUser($user);
                } else {
                    continue;
                }
            }
            $coupon->setCode($couponData['code']);
            $coupon->setPercent($couponData['percent']);
            $coupon->setStatus($couponData['status']);
            $coupon->setCreatedAt(new \DateTime());
            $coupon->setUpdatedAt(new \DateTime());

            $manager->persist($coupon);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test-task'];
    }
}
