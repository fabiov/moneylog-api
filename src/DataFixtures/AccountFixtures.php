<?php

namespace App\DataFixtures;

use App\Entity\Account;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $accountMarioConto = (new Account())
            ->setName('Conto corrente')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::MARIO_REFERENCE));
        $manager->persist($accountMarioConto);

        $accountMarioContanti = (new Account())
            ->setName('Contanti')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::MARIO_REFERENCE));
        $manager->persist($accountMarioContanti);

        $accountMarioRisparmi = (new Account())
            ->setName('Conto deposito')
            ->setRecap(false)
            ->setUser($this->getReference(UserFixtures::MARIO_REFERENCE));
        $manager->persist($accountMarioRisparmi);

        $accountGiuseppeConto = (new Account())
            ->setName('Conto corrente')
            ->setRecap(true)
            ->setUser($this->getReference(UserFixtures::GIUSEPPE_REFERENCE));
        $manager->persist($accountGiuseppeConto);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
