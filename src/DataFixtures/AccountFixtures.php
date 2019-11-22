<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [UserFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        // FABIO'S ACCOUNTS
        $account1UserFabio = (new Account())
            ->setName('Conto corrente - Fabio')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::USER_FABIO_REFERENCE));
        $manager->persist($account1UserFabio);

        $account2UserFabio = (new Account())
            ->setName('Contanti - Fabio')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::USER_FABIO_REFERENCE));
        $manager->persist($account2UserFabio);

        $account3UserFabio = (new Account())
            ->setName('Conto deposito - Fabio')
            ->setRecap(false)
            ->setUser($this->getReference(UserFixtures::USER_FABIO_REFERENCE));
        $manager->persist($account3UserFabio);

        // MARIO'S ACCOUNTS
        $account1UserMario = (new Account())
            ->setName('Conto corrente - Mario')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::USER_MARIO_REFERENCE));
        $manager->persist($account1UserMario);

        $account2UserMario = (new Account())
            ->setName('Contanti - Mario')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::USER_MARIO_REFERENCE));
        $manager->persist($account2UserMario);

        $manager->flush();
    }
}
