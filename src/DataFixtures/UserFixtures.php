<?php

namespace App\DataFixtures;

use App\Entity\Currency;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $qb = $manager->createQueryBuilder();
        $query = $qb->select('COUNT(u.id)')
            ->from(User::class, 'u')
            ->getQuery();
        $count = $query->getSingleScalarResult();
        if ($count > 0) {
            return;
        }

        $usersData = [
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'email' => 'john@example.com',
                'password' => 'password123',
                'email_verified_at' => '2024-05-24 12:00:00',
                'main_currency' => 'EUR',
                'status' => User::STATUS_ACTIVE,
            ], [
                'firstname' => 'Jane',
                'lastname' => 'Doe',
                'email' => 'jane@example.com',
                'password' => 'password456',
                'email_verified_at' => '2024-05-25 12:00:00',
                'main_currency' => 'EUR',
                'status' => User::STATUS_ACTIVE,
            ], [
                'firstname' => 'Alice',
                'lastname' => 'Smith',
                'email' => 'alice@example.com',
                'password' => 'password789',
                'email_verified_at' => '2024-05-26 12:00:00',
                'main_currency' => 'EUR',
                'status' => User::STATUS_INACTIVE,
            ], [
                'firstname' => 'Michael',
                'lastname' => 'Smith',
                'email' => 'michael@example.com',
                'password' => 'password789',
                'email_verified_at' => null,
                'main_currency' => 'FTN',
                'status' => User::STATUS_ACTIVE,
            ],
        ];

        foreach ($usersData as $userData) {
            $user = new User();
            $user->setFirstname($userData['firstname']);
            $user->setLastname($userData['lastname']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->passwordHasher->hashPassword(
                $user,
                $userData['password']
            ));
            $user->setEmailVerifiedAt($userData['email_verified_at'] ? new \DateTime($userData['email_verified_at']) : null);
            $currency = $manager->getRepository(Currency::class)->findOneBy(['code' => $userData['main_currency']]);
            if ($currency) {
                $user->setMainCurrency($currency);
            }
            $user->setStatus($userData['status']);
            $user->setCreatedAt(new \DateTime());
            $user->setUpdatedAt(new \DateTime());
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['test-task'];
    }
}
